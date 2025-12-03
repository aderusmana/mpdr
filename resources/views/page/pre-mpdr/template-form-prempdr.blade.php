<x-app-layout>
    @section('title')
        Template Form PREMPDR
    @endsection

    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap.min.css">
        <style>
            @media print {
                
                body {
                    zoom: 0.6; 
                    margin: 0px;
                }
                .no-print, .topbar, .left-sidebar{
                    display: none !important;
                }
                
            }
            #competitorProduct>tbody>tr>th, #competitorProduct>tbody>tr>td, #approver>tbody>tr>th, #approver>tbody>tr>td{
                padding: 8px 10px !important;
            }

        </style>
    @endpush


    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Template Form PREMPDR</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Template Form PREMPDR</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('assets') }}/images/breadcrumb/ChatBc.png" alt="modernize-img"
                            class="img-fluid mb-n4">
                    </div>
                </div>
                <div class="col-3">
                    <button onclick="history.back()" class="btn btn-sm btn-primary flex-end">Back</button>
                </div>
            </div>
        </div>
    </div>

    
    <div id="prempdr-form" class="border border-4 border-black w-100 mb-4">
        <header id="prempdr-header" class="row">
            <div class="col-3">
                <img src="{{ asset('assets') }}/images/logos/logoputih.png" class="dark-logo img-fluid p-0" alt="Logo-Dark">
                <img src="{{ asset('assets') }}/images/logos/logohitam.png" class="light-logo img-fluid p-0" alt="Logo-light">
            </div>
            <div class="col-5 fw-bold border-start border-end border-3 border-black text-center d-flex flex-column justify-content-center align-items-center">
                <h4>Pre-MARKETING</h4>
                <h4>PRODUCT DEVELOPMENT REQUEST</h4>
            </div>
            <div class="col-3 d-flex flex-column justify-content-around">
                    <p class="my-auto">No : <span id="revision-no"></span></p>
                    <p class="my-auto">Revision : <span id="revision-count"></span></p>
                    <p class="my-auto">Date : <span id="revision-date"></span></p>
            </div>
        </header>
        <main id="prempdr-main" class="d-flex flex-column gap-3 border-top border-4 border-black p-2">
            <div class="row">  
                <div class="col-12 col-sm-6 order-3 order-sm-1">
                    <label for="projectName" class="form-label">Project Name:</label>
                    <input type="text" class="form-control" name="projectName" id="projectName"> 
                </div>
                <div class="col-12 col-sm-3 order-2 order-sm-2">
                    <label for="levelPriority" class="form-label">Level Priority:</label>
                    <select class="form-select" name="levelPriority" aria-label="Level Priority" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="col-12 col-sm-3 d-flex justify-content-end order-1 order-sm-3 ">
                    <label for="projectName" class="form-label">No Reg: <span id="no_reg_text">25PREMPDR0000</span></label>
                    <input type="hidden" id="no_reg" name="no_reg" value="" readonly>
                </div>
            </div>  
            <div class="">
                <label for="brandName" class="form-label">Proposed BRAND Name:</label>
                <input type="text" class="form-control" name="initiator" id="brandName"> 
            </div>
            <div id="rational">
                <label class="form-label">Rational For Development: </label>
                <textarea class="form-control" id="rationalForDevelopment"></textarea>
            </div>
            <div class="row">
                <div id="productCategory" class="col">
                    <label class="form-label">Product Category:</label>
                    <div class="">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Margarine">
                            <label class="form-check-label" for="Margarine">
                            Margarine
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Frying Fats">
                            <label class="form-check-label" for="Frying Fats">
                            Frying Fats
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Shortening">
                            <label class="form-check-label" for="Shortening">
                            Shortening
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Pastry">
                            <label class="form-check-label" for="Pastry">
                            Pastry
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Others">
                            <label class="form-check-label" for="Others">
                                Others
                            </label>
                            <input type="text" class="form-control" name="productCategoryText" id="productCategoryText" placeholder="(Others)">
                        </div>
                    </div>
                </div>
                <div id="channel" class="col">
                    <label class="form-label">Channel:</label>
                    <div class="">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="channel" id="International">
                            <label class="form-check-label" for="International">
                            International
                            </label>
                            <input type="text" class="form-control" name="country" id="country" placeholder="Country">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="channel" id="Regional">
                            <label class="form-check-label" for="Regional">
                            Regional
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="channel" id="Industrial">
                            <label class="form-check-label" for="Industrial">
                            Industrial
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="channel" id="FSBC-Direct">
                            <label class="form-check-label" for="FSBC-Direct">
                            FSBC-Direct
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="channel" id="FSBC-Distributor">
                            <label class="form-check-label" for="FSBC-Distributor">
                            FSBC-Distributor
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <h5>GENERAL PRODUCT DESCRIPTION AND FUNCTION</h5>
            <div id="productDescriptionDiv">
                <label class="form-label">Product Description <span class="fw-normal">(Form/Color/Flavor brief)</span>: </label>
                <textarea class="form-control" id="productDescription"></textarea>
            </div>
            <div id="usage">
                <label class="form-label">Usage Description <span class="fw-normal">(Application in customer)</span>: </label>
                <textarea class="form-control" id="usageDescription"></textarea>
            </div>
            <div id="certification">
                <label class="form-label">Certification Requirement:</label>
                <div class="">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certification" id="BPOM">
                        <label class="form-check-label" for="BPOM">
                        BPOM
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certification" id="HALAL">
                        <label class="form-check-label" for="HALAL">
                        HALAL
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certification" id="certification3">
                        <label class="form-check-label" for="certification3">
                            Others
                        </label>
                        <input type="text" class="form-control" id="certificationText" name="certificationText" placeholder="(Others)">
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
                        <tr>
                            <td>1</td>
                            <td><input type="text" class="form-control" name="productName1" id="productName1" placeholder="Product Name"></td>
                            <td><input type="text" class="form-control" name="size1" id="size1"></td>
                            <td><input type="text" class="form-control" name="packaging1" id="packaging1"></td>
                            <td><input type="text" class="form-control" name="priceIndication1" id="priceIndication1"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><input type="text" class="form-control" name="productName2" id="productName2" placeholder="Product Name"></td>
                            <td><input type="text" class="form-control" name="size2" id="size2" ></td>
                            <td><input type="text" class="form-control" name="packaging2" id="packaging2"></td>
                            <td><input type="text" class="form-control" name="priceIndication2" id="priceIndication2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="detailedPackaging">
                <h5>Detailed Packaging Required:</h5>
                <ol>
                    <li>
                        <div class="">
                            <label for="weightProduct" class="form-label">Weight of Product (kg/carton):</label>
                            <input type="text" class="form-control" name="weightProduct" id="weightProduct"> 
                        </div>
                    </li>
                    <li>
                        <label class="form-label">Packaging:</label>
                        <div class="row">
                            <div class="col form-check">
                                <input class="form-check-input" type="radio" name="packaging" id="ExistingPackaging">
                                <label class="form-check-label" for="ExistingPackaging">
                                Existing
                                </label>
                                <input type="text" class="form-control" name="ExistingPackagingText" id="ExistingPackagingText" placeholder="Details">
                            </div>
                            <div class="col form-check">
                                <input class="form-check-input" type="radio" name="packaging" id="NewPackaging">
                                <label class="form-check-label" for="NewPackaging">
                                New
                                  </label>
                                <input type="text" class="form-control" name="NewPackagingText" id="NewPackagingText" placeholder="Details">
                            </div>
                        </div>
                    </li>
                    <li>
                        <div>
                            <label class="form-label">Product Variation List:</label>
                            <div>
                                <input type="text" class="form-control" name="productVariation" id="productVariation">
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
                            <input type="text" class="form-control" name="potentialVolume" id="potentialVolume"> 
                        </div>
                    </li>
                    <li>
                        <label class="form-label">Pricing Strategy</label>
                        <div class="row">
                            <div class="col form-check">
                                <label for="expectedMargin" class="form-label">Expected Margin (%):</label>
                                <input type="text" class="form-control" name="expectedMargin" id="expectedMargin">
                            </div>
                            <div class="col form-check">
                                <label for="priceEstimate" class="form-label">Price Estimate:</label>
                                <input type="text" class="form-control" name="priceEstimate" id="priceEstimate">
                            </div>
                        </div>
                    </li>
                </ol>
            </div>
            <div class="">
                <h5>TARGET LAUNCH (as Initiator request): </h5>
                <input type="text" class="form-control" name="targetLaunchText"  id="targetLaunch" placeholder="End wk of January - ETD Shipment (for first trial 1 FCL-2 FCL)">
            </div>
        </main>
        <footer id="prempdr-footer" class="p-2 mb-1" style="border-top: 3px solid black ;">
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
                <tbody class="">
                    <tr>
                        <td id="initiator">Initiator</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td id="salesManager">Dept. Head</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td id="marketingManager">Ass. Product Manager / Marketing Manager</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td id="deptHead">Dept. Head</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                </tbody>
            </table>
        </footer>
    </div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>
    <script>       

    </script>
@endpush

</x-app-layout>