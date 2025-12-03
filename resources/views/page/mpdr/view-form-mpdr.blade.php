<div id="prempdr-form" class=" border-none w-100 mb-4">
    <header id="prempdr-header" class="row p-2">
        <div class="col-3">
            <img src="{{ asset('assets') }}/images/logos/logoputih.png" class="dark-logo img-fluid p-0" alt="Logo-Dark">
            <!-- <img src="{{ asset('assets') }}/images/logos/logohitam.png" class="light-logo img-fluid p-0"alt="Logo-light"> -->
        </div>
        <div
            class="col-5 fw-bold border-start border-end  border-none text-center d-flex flex-column justify-content-center align-items-center">
            <h4>Pre-MARKETING</h4>
            <h4>PRODUCT DEVELOPMENT REQUEST</h4>
        </div>
        <div class="col-3 d-flex flex-column justify-content-around">
            <p class="my-auto">No : <span>{{ $formData->no }}</span></p>
            <p class="my-auto">Revision : <span id="revision-count">{{ $formData->revision->revision }}</span></p>
            <p class="my-auto">Updated : <span id="revision-date">{{ $formData->updated_at->diffForHumans() }}</span>
            </p>
        </div>
    </header>
    <main id="prempdr-main" class="d-flex flex-column gap-3 border-top border-4 border-black pt-3 p-2">
        <div class="row">
            <div class="col-6">
                <label for="projectName" class="form-label">Prouct Name:</label>
                <input type="text" class="form-control" name="projectName" id="projectName"
                    value="{{ $formData->product_name }}" readonly>
            </div>
            <div class="col-2">
                <label for="levelPriority" class="form-label">Level Priority:</label>
                <input type="text" class="form-control" name="levelPriority" id="levelPriority"
                    value="{{ $formData->level_priority }}" readonly>
            </div>
            <div class="col-4 justify-content-end">
                <label for="projectName" class="form-label" read>No Reg: </label>
                <input type="text" class="form-control" name="noReg" id="noReg" value="{{ $formData->no }}" readonly>
            </div>
        </div>
        <div id="rational">
            <label class="form-label">Rational For Development: </label>
            <textarea class="form-control no-resize" name="rationalForDevelopment" id="rationalForDevelopment" rows="2"
                readonly>{{ $formData->detail->rational_for_development }}</textarea>
        </div>
        <div class="row">
            <div id="productCategory" class="col">
                <label class="form-label">Product Category:</label>

                @php
                $category = $formData->category->category ?? '';

                $standardCategories = ['Margarine', 'Frying Fats', 'Shortening', 'Pastry'];

                $isOther = !in_array($category, $standardCategories) && !empty($category);
                @endphp

                <div class="">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="productCategory" id="Margarine"
                            value="Margarine" @if($category=='Margarine' ) checked @endif disabled>
                        <label class="form-check-label" for="Margarine">
                            Margarine
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="productCategory" id="Frying Fats"
                            value="Frying Fats" @if($category=='Frying Fats' ) checked @endif disabled>
                        <label class="form-check-label" for="Frying Fats">
                            Frying Fats
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="productCategory" id="Shortening"
                            value="Shortening" @if($category=='Shortening' ) checked @endif disabled>
                        <label class="form-check-label" for="Shortening">
                            Shortening
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="productCategory" id="Pastry" value="Pastry"
                            @if($category=='Pastry' ) checked @endif disabled>
                        <label class="form-check-label" for="Pastry">
                            Pastry
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="productCategory" id="Others" value="Others"
                            @if($isOther) checked @endif disabled>
                        <label class="form-check-label" for="Others">
                            Others
                        </label>
                        <input type="text" class="form-control" name="productCategoryText" id="productCategoryText"
                            value="{{ $isOther ? $category : '' }}" readonly>
                    </div>
                </div>
            </div>
            <div id="channel" class="col">
                <label class="form-label">Channel:</label>

                @php
                $channelCategory = $formData->channel->category ?? '';
                $countryName = $formData->channel->country ?? '';
                @endphp

                <div class="">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="channel" id="International"
                            value="International" @if($channelCategory=='International' ) checked @endif disabled>
                        <label class="form-check-label" for="International">
                            International
                        </label>
                        <input type="text" class="form-control" name="country" id="country"
                            value="{{ $channelCategory == 'International' ? $countryName : '' }}" readonly>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="channel" id="Regional" value="Regional"
                            @if($channelCategory=='Regional' ) checked @endif disabled>
                        <label class="form-check-label" for="Regional">
                            Regional
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="channel" id="Industrial" value="Industrial"
                            @if($channelCategory=='Industrial' ) checked @endif disabled>
                        <label class="form-check-label" for="Industrial">
                            Industrial
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="channel" id="FSBC-Direct" value="FSBC-Direct"
                            @if($channelCategory=='FSBC-Direct' ) checked @endif disabled>
                        <label class="form-check-label" for="FSBC-Direct">
                            FSBC-Direct
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="channel" id="FSBC-Distributor"
                            value="FSBC-Distributor" @if($channelCategory=='FSBC-Distributor' ) checked @endif disabled>
                        <label class="form-check-label" for="FSBC-Distributor">
                            FSBC-Distributor
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <h5>GENERAL PRODUCT DESCRIPTION AND FUNCTION</h5>
        <div id="productDescriptionDiv">
            <label class="form-label">Product Description <span class="fw-normal">(Form/Color/Flavor brief)</span>:
            </label>
            <textarea class="form-control no-resize" id="productDescription" rows="3"
                readonly>{{ $formData->description->product_description }}</textarea>
        </div>
        <div id="usage">
            <label class="form-label">Usage Description <span class="fw-normal">(Application in customer)</span>:
            </label>
            <input type="text" class="form-control" id="usageDescription"
                value="{{ $formData->description->usage_description }}" readonly>
        </div>
        <div class="row">
            <div class="col" id="storage">
                <label class="form-label">Storage Temperature: </label>
                <input type="text" class="form-control" name="storageTemperature" id="storageTemperature"
                    value="{{ $formData->description->storage_temperature }}" readonly>
            </div>
            <div class="col" id="delivery">
                <label class="form-label">Delivery Temperature: </label>
                <input type="text" class="form-control" name="deliveryTemperature" id="deliveryTemperature"
                    value="{{ $formData->description->delivery_temperature }}" readonly>
            </div>
        </div>
        <div id="certification">
            <label class="form-label">Certification Requirement:</label>

            @php
            $certCategory = $formData->certification->category ?? '';

            $certOtherText = $formData->certification->other ?? '';
            @endphp

            <div class="">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="certification" id="BPOM" value="BPOM"
                        @if($certCategory=='BPOM' ) checked @endif disabled>
                    <label class="form-check-label" for="BPOM">
                        BPOM
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="certification" id="HALAL" value="HALAL"
                        @if($certCategory=='HALAL' ) checked @endif disabled>
                    <label class="form-check-label" for="HALAL">
                        HALAL
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="certification" id="Others" value="Others"
                        @if($certCategory=='Others' ) checked @endif disabled>
                    <label class="form-check-label" for="Others">
                        Others
                    </label>
                    <input type="text" class="form-control" id="certificationText" name="certificationText"
                        value="{{ $certCategory == 'Others' ? $certOtherText : '' }}" readonly>
                </div>
            </div>
        </div>
        <div id="competitor">
            <h5>Competitor's Product to Match or to Beat:</h5>
            <table id="competitorProduct" class="table table-striped nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PRODUCT NAME</th>
                        <th>SIZE</th>
                        <th>PACKAGING</th>
                        <th>Price Indication</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($formData->competitor as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><input type="text" class="form-control" value="{{ $item->name }}" readonly></td>
                        <td><input type="text" class="form-control" value="{{ $item->size }}" readonly></td>
                        <td><input type="text" class="form-control" value="{{ $item->packaging }}" readonly></td>
                        <td><input type="text" class="form-control" value="{{ $item->price }}" readonly></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">there are no competitor</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div id="detailedPackaging">
            <h5>Detailed Packaging Required:</h5>
            <ol>
                <li>
                    <div class="">
                        <label for="weightProduct" class="form-label">Weight of Product (kg/carton):</label>
                        <input type="text" class="form-control" name="weightProduct" id="weightProduct"
                            value="{{ $formData->packaging->weight }}" readonly>
                    </div>
                </li>
                <li>
                    <label class="form-label">Packaging:</label>

                    @php
                    $packagingType = $formData->packaging->category ?? '';
                    $packagingDesc = $formData->packaging->detail ?? '';
                    @endphp
                    <div class="row">
                        <div class="col form-check">
                            <input class="form-check-input" type="radio" name="packaging" id="ExistingPackaging" value="Existing"
                                @if($packagingType=='Existing' ) checked @endif disabled>
                            <label class="form-check-label" for="ExistingPackaging">
                                Existing
                            </label>
                            <textarea class="form-control no-resize" name="ExistingPackagingText" id="ExistingPackagingText" rows="2"
                                readonly>{{ $packagingType == 'Existing' ? $packagingDesc : '' }}</textarea>
                        </div>
                        <div class="col form-check">
                            <input class="form-check-input" type="radio" name="packaging" id="NewPackaging" value="New"
                                @if($packagingType=='New' ) checked @endif disabled>
                            <label class="form-check-label" for="NewPackaging">
                                New
                            </label>
                            <textarea class="form-control no-resize" name="NewPackagingText" id="NewPackagingText" rows="2"
                                readonly>{{ $packagingType == 'New' ? $packagingDesc : '' }}</textarea>
                        </div>
                    </div>
                </li>
                <li>
                    <div>
                        <label class="form-label">Product Variation List:</label>
                        <div>
                            <input type="text" class="form-control" name="productVariation" id="productVariation"
                                value="{{ $formData->packaging->product_variation }}" readonly>
                        </div>
                    </div>
                </li>
            </ol>
        </div>
        <div id="potential">
            <h5>MARKET UPDATE</h5>
            <ol>
                <li>
                    <div class="form-chcek">
                        <label for="potentialVolume" class="form-label">Potential Volume (Mt/Annually):</label>
                        <input type="text" class="form-control" name="potentialVolume" id="potentialVolume"
                            value="{{ $formData->market->potential_volume }}" readonly>
                    </div>
                </li>
                <li>
                    <label class="form-label">Pricing Strategy</label>
                    <div class="row">
                        <div class="col form-check">
                            <label for="expectedMargin" class="form-label">Expected Margin (%):</label>
                            <input type="text" class="form-control" name="expectedMargin" id="expectedMargin"
                                value="{{  $formData->market->expected_margin }}" readonly>
                        </div>
                        <div class="col form-check">
                            <label for="priceEstimate" class="form-label">Price Estimate:</label>
                            <input type="text" class="form-control" name="priceEstimate" id="priceEstimate"
                                value="{{ $formData->market->price_estimate }}" readonly>
                        </div>
                    </div>
                </li>
            </ol>
        </div>
        <div class="">
            <h5>TARGET LAUNCH (as Initiator request): </h5>
            <input type="text" class="form-control" name="targetLaunchText" id="targetLaunch"
                value="{{ $formData->detail->target_launch }}" readonly>
        </div>
    </main>
    <footer id="prempdr-footer" class="p-2 mt-4" style="border-top: 3px solid black ;">
        <table id="approver" class="table table-striped nowrap">
            <thead>
                <tr>
                    <th>Approved by,</th>
                    <th class="text-center">Approved</th>
                    <th class="text-center">Approved with Review</th>
                    <th class="text-center">Not Approved</th>
                    <th class="text-center">Notes/Comments</th>
                </tr>
            </thead>
            <tbody id="approver-body">
                @forelse ($approver as $item)
                <tr>
                    <td>{{ $item->approver_name }}</td>

                    <td class="text-center">
                        {{ $item->status == 'approve' ? '✓' : '-' }}
                    </td>

                    <td class="text-center">
                        {{ $item->status == 'approve with review' ? '✓' : '-' }}
                    </td>

                    <td class="text-center">
                        {{ $item->status == 'not approved' ? '✓' : '-' }}
                    </td>

                    <td class="text-center">
                        {{ $item->comment ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">there are no approver.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </footer>
</div>