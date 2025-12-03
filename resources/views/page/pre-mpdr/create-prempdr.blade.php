<x-app-layout>
    @section('title')
        Create PREMPDR
    @endsection


    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap.min.css">
        <style>
            .no-resize{
                resize:none;
            }
        </style>
    @endpush


    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Create PREMPDR</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Create PREMPDR</li>
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

    <div id="content" class="w-100">
        <form id="form" class="" action="{{ route('prempdr.store') }}" method="POST">
            @csrf
            <main id="prempdr-main" class="d-flex flex-column gap-3 p-2">
                <div class="d-flex justify-content-end">
                    <label for="no_reg" class="form-label">No Reg: <span id="no_reg_text"></span></label>
                    <input type="hidden" id="no_reg" name="no_reg" value="" readonly>
                </div>
                <div class="row">
                    <div class="col-12 col-md-5">
                        <label for="projectName" class="form-label">Project Name:</label>
                        <input type="text" class="form-control" name="projectName" required>
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="levelPriority" class="form-label">Level Priority:</label>
                        <select class="form-select" name="levelPriority" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="brandName" class="form-label">Proposed BRAND Name:</label>
                        <input type="text" class="form-control" name="brandName" required>
                    </div>
                </div>
                <div id="rational">
                    <label class="form-label">Rational For Development: </label>
                    <textarea class="form-control no-resize" name="rationalForDevelopment" id="rationalForDevelopment" rows="2" required></textarea>
                </div>
                <div class="row">
                    <div id="productCategory" class="col">
                        <label class="form-label">Product Category:</label>
                        <div class="">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="productCategory1" value="Margarine" required>
                                <label class="form-check-label" for="productCategory1">
                                Margarine
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="productCategory2" value="Frying Fats">
                                <label class="form-check-label" for="productCategory2">
                                Frying Fats
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="productCategory3" value="Shortening">
                                <label class="form-check-label" for="productCategory3">
                                Shortening
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="productCategory4" value="Pastry">
                                <label class="form-check-label" for="productCategory4">
                                Pastry
                                </label>
                            </div>
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-2" type="radio" name="productCategory" id="productCategory5" value="Others">
                                <label class="form-check-label me-2" for="productCategory5">
                                    Others
                                </label>
                                <input type="text" class="form-control w-30" id="productCategoryText" name="productCategoryText" placeholder="(Others)" readonly>
                            </div>
                        </div>
                    </div>
                    <div id="channel" class="col">
                        <label class="form-label">Channel:</label>
                        <div class="">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-2" type="radio" name="channel" id="channel1" value="International" required>
                                <label class="form-check-label me-2" for="channel1">
                                International
                                </label>
                                <input type="text" class="form-control w-30" name="country" id="country" placeholder="Country" readonly>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="channel2" value="Regional">
                                <label class="form-check-label" for="channel2">
                                Regional
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="channel3" value="Industrial">
                                <label class="form-check-label" for="channel3">
                                Industrial
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="channel4" value="FSBC-Direct">
                                <label class="form-check-label" for="channel4">
                                FSBC-Direct
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="channel5" value="FSBC-Distributor">
                                <label class="form-check-label" for="channel5">
                                FSBC-Distributor
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <h5>GENERAL PRODUCT DESCRIPTION AND FUNCTION</h5>
                <div id="productDescriptionDiv">
                    <label class="form-label">Product Description <span class="fw-normal">(Form/Color/Flavor brief)</span>: </label>
                    <textarea class="form-control no-resize" name="productDescription" id="productDescription" rows="3" required></textarea>
                </div>
                <div id="usage">
                    <label class="form-label">Usage Description <span class="fw-normal">(Application in customer)</span>: </label>
                    <input type="text" class="form-control" name="usageDescription" id="usageDescription" required>
                </div>
                <div class="row">
                    <div class="col" id="storage">
                        <label class="form-label">Storage Temperature: </label>
                        <input type="text" class="form-control" name="storageTemperature" id="storageTemperature" required>
                    </div>
                    <div class="col" id="delivery">
                        <label class="form-label">Delivery Temperature: </label>
                        <input type="text" class="form-control" name="deliveryTemperature" id="deliveryTemperature" required>
                    </div>
                </div>
                <div id="certification">
                    <label class="form-label">Certification Requirement:</label>
                    <div class="">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="certification" id="certification1" value="BPOM" required>
                            <label class="form-check-label" for="certification1">
                            BPOM
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="certification" id="certification2" value="HALAL">
                            <label class="form-check-label" for="certification2">
                            HALAL
                            </label>
                        </div>
                        <div class="form-check d-flex align-items-center">
                            <input class="form-check-input me-2" type="radio" name="certification" id="certification3" value="Others">
                            <label class="form-check-label me-2" for="certification3">
                                Others
                            </label>
                            <input type="text" class="form-control w-30" name="certificationText" id="certificationText" placeholder="(Others)" readonly>
                        </div>
                    </div>
                </div>
                <div id="competitor">
                    <h5>Competitor's Product to Match or to Beat:</h5>
                    <table id="competitorProduct" class="table table-striped nowrap" style="width:100%">
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
                                <td><input type="text" class="form-control" name="productName1" id="" placeholder="Product Name"></td>
                                <td><input type="text" class="form-control" name="size1" id=""></td>
                                <td><input type="text" class="form-control" name="packaging1" id=""></td>
                                <td><input type="text" class="form-control" name="priceIndication1" id=""></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><input type="text" class="form-control" name="productName2" id="" placeholder="Product Name"></td>
                                <td><input type="text" class="form-control" name="size2" id="" ></td>
                                <td><input type="text" class="form-control" name="packaging2" id=""></td>
                                <td><input type="text" class="form-control" name="priceIndication2" id=""></td>
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
                                <input type="number" class="form-control" name="weightProduct" required>
                            </div>
                        </li>
                        <li>
                            <label class="form-label">Packaging:</label>
                            <div class="row">
                                <div class="form-check col">
                                    <input class="form-check-input" type="radio" name="packaging" id="existingPackaging" value="Existing" required>
                                    <label class="form-check-label" for="existingPackaging">
                                    Existing
                                    </label>
                                    <textarea class="form-control no-resize" name="ExistingPackagingText" id="ExistingPackagingText" placeholder="Details" rows="2" readonly></textarea>
                                </div>
                                <div class="form-check col">
                                    <input class="form-check-input" type="radio" name="packaging" id="newPackaging" value="New">
                                    <label class="form-check-label" for="newPackaging">
                                    New
                                    </label>
                                    <textarea class="form-control no-resize" name="NewPackagingText" id="NewPackagingText" placeholder="Details" rows="2" readonly></textarea>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div>
                                <label class="form-label">Product Variation List:</label>
                                <div>
                                    <input type="text" class="form-control" name="productVariation" id="" required>
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
                                <input type="number" class="form-control" name="potentialVolume" required>
                            </div>
                        </li>
                        <li>
                            <label class="form-label">Pricing Strategy</label>
                            <div class="row">
                                <div class="form-check col">
                                    <label for="expectedMargin" class="form-label">Expected Margin (%):</label>
                                    <input type="text" class="form-control" name="expectedMargin" required>
                                </div>
                                <div class="form-check col">
                                    <label for="priceEstimate" class="form-label">Price Estimate:</label>
                                    <input type="text" class="form-control" name="priceEstimate" required>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="">
                    <h5>TARGET LAUNCH (as Initiator request): </h5>
                    <input type="text" class="form-control" name="targetLaunchText"  placeholder="End wk of January - ETD Shipment (for first trial 1 FCL-2 FCL)" required>
                </div>
            </main>

            <footer class="border-top border-3 border-black mt-2">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Choose: </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Initiator</td>
                            <td class="text-center">
                                <span style="font-weight: bold; font-size: 1rem; cursor: pointer;">{{ optional(Auth::user()->atasan)->name ?? 'atasan tidak ditemukan' }}</span>
                                <!-- <input type="text" name="initiator" class="form-input form-input-sm" value="{{ Auth::user()->atasan_nik }}" hidden> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </footer>

            <div class="w-100 d-flex align-items-center justify-content-center mb-5">
                <button type="submit" name="form_status" value="Draft" class="btn btn-primary me-3">Save as Draft</button>
                <button type="submit" name="form_status" value="Submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>
    <script>
        // membuat table menjadi responsive
        var table = $('#competitorProduct').DataTable({
            responsive: true,
            ordering: false,
            paging: false,
            searching: false,
            info: false
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Mengambil no_reg yang baru
            fetch('{{ route('prempdr.noReg') }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('no_reg_text').innerHTML = data.no_reg;
                        document.getElementById('no_reg').value = data.no_reg;
                    })
                    .catch(error => {
                        console.error('Error fetching no_reg:', error);
                    });


            // mengambil list approver
            $.ajax({
                url: '{{ route('prempdr.approver.list.data') }}',
                method: 'GET',
                success: function(response) {
                    response.forEach(item => {
                        $('#initiator').append($('<option>', {
                            value: item.nik,
                            text: item.name
                        }));
                    });
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil approver list');
                    // $('#formData').html('<p>There was an error fetching the data.</p>');
                }
            });
        });

        // mengatur readonly text pada input product category
        const productCategoryRadios = document.querySelectorAll('input[name="productCategory"]');
        const productCategoryText = document.getElementById('productCategoryText');
        productCategoryRadios.forEach(radio => {
            radio.addEventListener('change', () => {
            if (radio.checked && radio.value == 'Others') {
                productCategoryText.removeAttribute('readonly');
            } else {
                productCategoryText.setAttribute('readonly', true);
                productCategoryText.value = '';
            }
            });
        });

        // mengatur readonly text pada input channel
        const channelRadios = document.querySelectorAll('input[name="channel"]');
        const channelText = document.getElementById('country');
        channelRadios.forEach(radio => {
            radio.addEventListener('change', () => {
            if (radio.checked && radio.value == 'International') {
                channelText.removeAttribute('readonly');
            } else {
                channelText.setAttribute('readonly', true);
                channelText.value = '';
            }
            });
        });

        // mengatur readonly text pada input certification
        const certificationRadios = document.querySelectorAll('input[name="certification"]');
        const certificationText = document.getElementById('certificationText');
        certificationRadios.forEach(radio => {
            radio.addEventListener('change', () => {
            if (radio.checked && radio.value == 'Others') {
                certificationText.removeAttribute('readonly');
            } else {
                certificationText.setAttribute('readonly', true);
                certificationText.value = '';
            }
            });
        });

        // mengatur readonly text pada input packaging
        const packagingRadios = document.querySelectorAll('input[name="packaging"]');
        packagingRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                var existingPackagingText = document.getElementById('ExistingPackagingText');
                var newPackagingText = document.getElementById('NewPackagingText');
                if (radio.checked && radio.value == 'Existing') {
                    existingPackagingText.removeAttribute('readonly');
                    newPackagingText.setAttribute('readonly', true);
                    newPackagingText.value = '';
                } else {
                    newPackagingText.removeAttribute('readonly');
                    existingPackagingText.setAttribute('readonly', true);
                    existingPackagingText.value = '';
                }
            });
        });
    </script>
@endpush
</x-app-layout>
