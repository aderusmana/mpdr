<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ConditionQueryTrait
{
    /**
     * Apply DataTables SearchBuilder conditions dynamically to a query instance.
     */
    public function applySearchBuilder($query, $searchBuilder, array $columnMapping = [])
    {
        if (empty($searchBuilder) || !is_array($searchBuilder)) {
            return $query;
        }

        $criteria = $searchBuilder['criteria'] ?? [];
        if (empty($criteria) || !is_array($criteria)) {
            return $query;
        }

        $query->where(function ($groupQuery) use ($criteria, $searchBuilder, $columnMapping) {
            foreach ($criteria as $item) {
                if (isset($item['criteria']) && is_array($item['criteria'])) {
                    $subLogic = strtolower($item['logic'] ?? 'and') === 'or' ? 'orWhere' : 'where';
                    $groupQuery->$subLogic(function ($subQ) use ($item, $columnMapping) {
                        $this->applySearchBuilder($subQ, $item, $columnMapping);
                    });
                } elseif (isset($item['data']) && isset($item['condition'])) {
                    $rawColumn = $item['data'];
                    $column = $columnMapping[$rawColumn] ?? $rawColumn;
                    $condition = $item['condition'];
                    $value = $item['value'][0] ?? null;
                    $values = $item['value'] ?? [];
                    $type = $item['type'] ?? 'string';
                    $isDate = in_array($type, ['date', 'moment', 'luxon', 'num-fmt']);

                    $this->applyCondition($groupQuery, $column, $condition, $value, $values, $isDate);
                }
            }
        });

        return $query;
    }

    public function applyCondition($query, $column, $condition, $value, $values, $isDate = false)
    {
        if ($isDate) {
            switch ($condition) {
                case 'between':
                    if (isset($values[1])) {
                        $startVal = strtotime($values[0]);
                        $endVal = strtotime($values[1]);
                        if ($startVal !== false && $endVal !== false) {
                            $start = date('Y-m-d 00:00:00', $startVal);
                            $end = date('Y-m-d 23:59:59', $endVal);
                            $query->whereBetween($column, [$start, $end]);
                        } else {
                            $query->whereBetween($column, [$values[0], $values[1]]);
                        }
                    }
                    break;
                case '!between':
                    if (isset($values[1])) {
                        $startVal = strtotime($values[0]);
                        $endVal = strtotime($values[1]);
                        if ($startVal !== false && $endVal !== false) {
                            $start = date('Y-m-d 00:00:00', $startVal);
                            $end = date('Y-m-d 23:59:59', $endVal);
                            $query->whereNotBetween($column, [$start, $end]);
                        } else {
                            $query->whereNotBetween($column, [$values[0], $values[1]]);
                        }
                    }
                    break;
                case '>':
                case '<':
                case '>=':
                case '<=':
                case '=':
                    $query->whereDate($column, $condition, $value);
                    break;
                case '!=':
                case 'not':
                    $query->whereDate($column, '!=', $value);
                    break;
                case 'null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNull($column)->orWhere($column, '');
                    });
                    break;
                case '!null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNotNull($column)->where($column, '!=', '');
                    });
                    break;
            }
        } else {
            // Special handling untuk kolom no_receiver yang berformat JSON
            if ($column === 'no_receiver') {
                switch ($condition) {
                    case '=':
                    case 'equals':
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonContains('no_receiver', $value)
                                ->orWhere('no_receiver', $value)
                                ->orWhere('no_receiver', 'LIKE', "%\"$value\"%");
                        });
                        break;
                    case '!=':
                    case 'not':
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonDoesntContain('no_receiver', $value)
                                ->where('no_receiver', '!=', $value)
                                ->where('no_receiver', 'NOT LIKE', "%\"$value\"%");
                        });
                        break;
                    case 'contains':
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonContains('no_receiver', $value)
                                ->orWhere('no_receiver', 'LIKE', "%$value%");
                        });
                        break;
                    case '!contains':
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonDoesntContain('no_receiver', $value)
                                ->where('no_receiver', 'NOT LIKE', "%$value%");
                        });
                        break;
                    default:
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonContains('no_receiver', $value)
                                ->orWhere('no_receiver', $value)
                                ->orWhere('no_receiver', 'LIKE', "%\"$value\"%");
                        });
                        break;
                }
            } else {
                // Standard handling untuk kolom lainnya
                switch ($condition) {
                    case 'between':
                        if (isset($values[1])) {
                            $query->whereBetween($column, [$values[0], $values[1]]);
                        }
                        break;
                    case '!between':
                        if (isset($values[1])) {
                            $query->whereNotBetween($column, [$values[0], $values[1]]);
                        }
                        break;
                    case '=':
                    case 'equals':
                        $query->where($column, '=', $value);
                        break;
                    case '!=':
                    case 'not':
                        $query->where($column, '!=', $value);
                        break;
                    case 'contains':
                        $query->where($column, 'LIKE', "%$value%");
                        break;
                    case '!contains':
                        $query->where($column, 'NOT LIKE', "%$value%");
                        break;
                    case 'starts':
                        $query->where($column, 'LIKE', "$value%");
                        break;
                    case '!starts':
                        $query->where($column, 'NOT LIKE', "$value%");
                        break;
                    case 'ends':
                        $query->where($column, 'LIKE', "%$value");
                        break;
                    case '!ends':
                        $query->where($column, 'NOT LIKE', "%$value");
                        break;
                    case '>':
                    case '<':
                    case '>=':
                    case '<=':
                        $query->where($column, $condition, $value);
                        break;
                    case 'null':
                        $query->where(function ($q) use ($column) {
                            $q->whereNull($column)->orWhere($column, '');
                        });
                        break;
                    case '!null':
                        $query->where(function ($q) use ($column) {
                            $q->whereNotNull($column)->where($column, '!=', '');
                        });
                        break;
                }
            }
        }
    }

    public function applyConditionPR($query, $column, $condition, $value, $values, $isDate = false)
    {
        if ($isDate) {
            switch ($condition) {
                case 'between':
                    if (isset($values[1])) {
                        $startVal = strtotime($values[0]);
                        $endVal = strtotime($values[1]);
                        if ($startVal !== false && $endVal !== false) {
                            $start = date('Y-m-d 00:00:00', $startVal);
                            $end = date('Y-m-d 23:59:59', $endVal);
                            $query->whereBetween($column, [$start, $end]);
                        } else {
                            $query->whereBetween($column, [$values[0], $values[1]]);
                        }
                    }
                    break;
                case '!between':
                    if (isset($values[1])) {
                        $startVal = strtotime($values[0]);
                        $endVal = strtotime($values[1]);
                        if ($startVal !== false && $endVal !== false) {
                            $start = date('Y-m-d 00:00:00', $startVal);
                            $end = date('Y-m-d 23:59:59', $endVal);
                            $query->whereNotBetween($column, [$start, $end]);
                        } else {
                            $query->whereNotBetween($column, [$values[0], $values[1]]);
                        }
                    }
                    break;
                case '>':
                case '<':
                case '>=':
                case '<=':
                case '=':
                    $query->whereDate($column, $condition, $value);
                    break;
                case '!=':
                case 'not':
                    $query->whereDate($column, '!=', $value);
                    break;
                case 'null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNull($column)->orWhere($column, '');
                    });
                    break;
                case '!null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNotNull($column)->where($column, '!=', '');
                    });
                    break;
            }
        } else {
            // Special handling for string "true"/"false" columns
            $booleanColumns = ['rqmDirect', 'rqm__log01'];
            if (in_array($column, $booleanColumns)) {
                switch ($condition) {
                    case '=':
                    case 'equals':
                        $query->where($column, strtolower($value) === 'true' ? 'true' : 'false');
                        break;
                    case '!=':
                    case 'not':
                        $query->where($column, '!=', strtolower($value) === 'true' ? 'true' : 'false');
                        break;
                    case 'null':
                        $query->where(function ($q) use ($column) {
                            $q->whereNull($column)->orWhere($column, '');
                        });
                        break;
                    case '!null':
                        $query->where(function ($q) use ($column) {
                            $q->whereNotNull($column)->where($column, '!=', '');
                        });
                        break;
                }
            } else {
                // Standard handling untuk kolom lainnya
                switch ($condition) {
                    case 'between':
                        if (isset($values[1])) {
                            $query->whereBetween($column, [$values[0], $values[1]]);
                        }
                        break;
                    case '!between':
                        if (isset($values[1])) {
                            $query->whereNotBetween($column, [$values[0], $values[1]]);
                        }
                        break;
                    case '=':
                    case 'equals':
                        $query->where($column, '=', $value);
                        break;
                    case '!=':
                    case 'not':
                        $query->where($column, '!=', $value);
                        break;
                    case 'contains':
                        $query->where($column, 'LIKE', "%$value%");
                        break;
                    case '!contains':
                        $query->where($column, 'NOT LIKE', "%$value%");
                        break;
                    case 'starts':
                        $query->where($column, 'LIKE', "$value%");
                        break;
                    case '!starts':
                        $query->where($column, 'NOT LIKE', "$value%");
                        break;
                    case 'ends':
                        $query->where($column, 'LIKE', "%$value");
                        break;
                    case '!ends':
                        $query->where($column, 'NOT LIKE', "%$value");
                        break;
                    case '>':
                    case '<':
                    case '>=':
                    case '<=':
                        $query->where($column, $condition, $value);
                        break;
                    case 'null':
                        $query->where(function ($q) use ($column) {
                            $q->whereNull($column)->orWhere($column, '');
                        });
                        break;
                    case '!null':
                        $query->where(function ($q) use ($column) {
                            $q->whereNotNull($column)->where($column, '!=', '');
                        });
                        break;
                }
            }
        }
    }
    public function applyConditionPO($query, $column, $condition, $value, $values, $isDate = false)
    {
        if ($isDate) {
            switch ($condition) {
                case 'between':
                    if (isset($values[1])) {
                        $query->whereBetween($column, [$values[0], $values[1]]);
                    }
                    break;
                case '!between':
                    if (isset($values[1])) {
                        $query->whereNotBetween($column, [$values[0], $values[1]]);
                    }
                    break;
                case '>':
                case '<':
                case '>=':
                case '<=':
                case '=':
                case 'equals':
                    $query->whereDate($column, $condition === 'equals' ? '=' : $condition, $value);
                    break;
                case '!=':
                case 'not':
                    $query->whereDate($column, '!=', $value);
                    break;
                case 'starts':
                    $query->whereDate($column, 'LIKE', "$value%");
                    break;
                case '!starts':
                    $query->whereDate($column, 'NOT LIKE', "$value%");
                    break;
                case 'null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNull($column)->orWhere($column, '');
                    });
                    break;
                case '!null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNotNull($column)->where($column, '!=', '');
                    });
                    break;
            }
        } else {
            // Standard handling untuk kolom string
            switch ($condition) {
                case 'between':
                    if (isset($values[1])) {
                        $query->whereBetween($column, [$values[0], $values[1]]);
                    }
                    break;
                case '!between':
                    if (isset($values[1])) {
                        $query->whereNotBetween($column, [$values[0], $values[1]]);
                    }
                    break;
                case '=':
                case 'equals':
                    $query->where($column, '=', $value);
                    break;
                case '!=':
                case 'not':
                    $query->where($column, '!=', $value);
                    break;
                case 'contains':
                    $query->where($column, 'LIKE', "%$value%");
                    break;
                case '!contains':
                    $query->where($column, 'NOT LIKE', "%$value%");
                    break;
                case 'starts':
                    $query->where($column, 'LIKE', "$value%");
                    break;
                case '!starts':
                    $query->where($column, 'NOT LIKE', "$value%");
                    break;
                case 'ends':
                    $query->where($column, 'LIKE', "%$value");
                    break;
                case '!ends':
                    $query->where($column, 'NOT LIKE', "%$value");
                    break;
                case '>':
                case '<':
                case '>=':
                case '<=':
                    $query->where($column, $condition, $value);
                    break;
                case 'null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNull($column)->orWhere($column, '');
                    });
                    break;
                case '!null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNotNull($column)->where($column, '!=', '');
                    });
                    break;
            }
        }
    }
}
