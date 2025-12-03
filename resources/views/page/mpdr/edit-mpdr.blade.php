<x-app-layout>
    @section('title')
        Edit MPDR
    @endsection

    @push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap.min.css">
    <style>
        @media print {
            body {
                zoom: 0.6; /* Mengubah skala menjadi 80% */
                margin: 0px;
            }
            .no-print{
                display: none;
            }
        }
        .no-resize{
            resize: none;
        }
    </style>
@endpush


    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Edit MPDR</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Edit MPDR</li>
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
        <form id="form" action="{{ route('mpdr.update', ['no_reg', $no_reg]) }}" method="POST">
            @csrf
            @method('PATCH')
            <main id="mpdr-main" class="d-flex flex-column gap-3 p-2">
                <div class="d-flex justify-content-end">
                    <label for="projectName" class="form-label">No Reg: <span id="no_reg_text"></span></label>
                    <input type="hidden" id="no_reg" name="no_reg" value="" readonly>
                </div>
                <div class="row">
                    <div class="col-12 col-md-5">
                        <label for="productName" class="form-label">Product Name:</label>
                        <input type="text" class="form-control" name="productName" id="productName" required> 
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="levelPriority" class="form-label">Level Priority:</label>
                        <select class="form-select" name="levelPriority" id="levelPriority" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="initiator" class="form-label">Initiator:</label>
                        <input type="text" id="initiator_name_display" class="form-control" value="Initiator name will appear here" readonly>
                        <input type="hidden" name="initiator" id="initiator_nik_value">
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
                                <input class="form-check-input" type="radio" name="productCategory" id="Margarine" value="Margarine" required>
                                <label class="form-check-label" for="productCategory1">
                                Margarine
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="Frying Fats" value="Frying Fats">
                                <label class="form-check-label" for="productCategory2">
                                Frying Fats
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="Shortening" value="Shortening">
                                <label class="form-check-label" for="productCategory3">
                                Shortening
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="Pastry" value="Pastry">
                                <label class="form-check-label" for="productCategory4">
                                Pastry
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="Others" value="Others">
                                <label class="form-check-label" for="productCategory5">
                                    Others
                                </label>
                                <input type="text" class="form-control" name="productCategoryText" placeholder="(Others)">
                            </div>
                        </div>
                    </div>
                    <div id="channel" class="col">
                        <label class="form-label">Channel:</label>
                        <div class="">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="International" value="International" required>
                                <label class="form-check-label" for="channel1">
                                International
                                </label>
                                <input type="text" class="form-control" name="country" id="country" placeholder="Country">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="Regional" value="Regional">
                                <label class="form-check-label" for="channel2">
                                Regional
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="Industrial" value="Industrial">
                                <label class="form-check-label" for="channel3">
                                Industrial
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="FSBC-Direct" value="FSBC-Direct">
                                <label class="form-check-label" for="channel4">
                                FSBC-Direct
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="FSBC-Distributor" value="FSBC-Distributor">
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
                    <textarea name="productDescription" id="productDescription" required></textarea>
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
                            <input class="form-check-input" type="radio" name="certification" id="BPOM" value="BPOM" required>
                            <label class="form-check-label" for="certification1">
                            BPOM
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="certification" id="HALAL" value="HALAL">
                            <label class="form-check-label" for="certification2">
                            HALAL
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="certification" id="certificationOthers" value="Others">
                            <label class="form-check-label" for="certification3">
                                Others
                            </label>
                            <input type="text" class="form-control" name="certificationText" placeholder="(Others)">
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
                                <input type="number" class="form-control" name="weightProduct" id="weightProduct" required> 
                            </div>
                        </li>
                        <li>
                            <label class="form-label">Packaging:</label>
                            <div class="row">
                                <div class="form-check col">
                                    <input class="form-check-input" type="radio" name="packaging" id="ExistingPackaging" value="Existing" required>
                                    <label class="form-check-label" for="ExistingPackaging">
                                    Existing
                                    </label>
                                    <textarea class="form-control no-resize" name="ExistingPackagingText" id="ExistingPackagingText" placeholder="Details" rows="2"></textarea>
                                </div>
                                <div class="form-check col">
                                    <input class="form-check-input" type="radio" name="packaging" id="NewPackaging" value="New">
                                    <label class="form-check-label" for="NewPackaging">
                                    New
                                    </label>
                                    <textarea class="form-control no-resize" name="NewPackagingText" id="NewPackagingText" placeholder="Details" rows="2"></textarea>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div>
                                <label class="form-label">Product Variation List:</label>
                                <div>
                                    <input type="text" class="form-control" name="productVariation" id="productVariation" required>
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
                                <input type="number" class="form-control" name="potentialVolume" id="potentialVolume" required> 
                            </div>
                        </li>
                        <li>
                            <label class="form-label">Pricing Strategy</label>
                            <div class="row">
                                <div class="form-check col">
                                    <label for="expectedMargin" class="form-label">Expected Margin (%):</label>
                                    <input type="text" class="form-control" name="expectedMargin" id="expectedMargin" required>
                                </div>
                                <div class="form-check col">
                                    <label for="priceEstimate" class="form-label">Price Estimate:</label>
                                    <input type="text" class="form-control" name="priceEstimate" id="priceEstimate" required>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="">
                    <h5>TARGET LAUNCH (as Initiator request): </h5>
                    <input type="text" class="form-control" name="targetLaunchText" id="targetLaunch" placeholder="End wk of January - ETD Shipment (for first trial 1 FCL-2 FCL)" required>
                </div>
            </main>

            <div class="w-100 d-flex align-items-center justify-content-center mb-5">
                <button type="submit" name="form_status" value="Draft" class="btn btn-primary me-3">Save as Draft</button>
                <button type="submit" name="form_status" value="Submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>

    
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.6.0/tinymce.min.js" integrity="sha512-/4EpSbZW47rO/cUIb0AMRs/xWwE8pyOLf8eiDWQ6sQash5RP1Cl8Zi2aqa4QEufjeqnzTK8CLZWX7J5ZjLcc1Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>
    <script>
        function submitForm(value) {
            // SweetAlert2 confirmation dialog for submit action
            const input = document.getElementById('form_status');
            input.value = value;
            Swal.fire({
                title: "Form " + value,
                text: "Are you sure you want to " + value + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#26D639',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#form").submit();
                }
            });
        }

        // initialize text editor tinymce
        tinymce.init({
            selector: '#productDescription',  // Menentukan elemen yang ingin diubah menjadi editor
            menubar: false,  // Menghilangkan menu bar jika tidak diperlukan
            plugins: 'lists link image', // Menambahkan plugin untuk daftar, tautan, dan gambar
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | numlist bullist | link image', // Tombol toolbar yang ditampilkan
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save(); // Ensure the textarea value is updated
                });
            }  
        });

        // membuat table menjadi responsive
        var table = $('#competitorProduct').DataTable({
            responsive: true,
            ordering: false,
            paging: false, 
            searching: false,
            info: false 
        });


        // fungsi untuk radiotext menjadi readonly jika radio tidak dipilih
        function radiosOnChanges() {
            // mengatur readonly text pada input product category
            $('input[name="productCategory"]').on('change', function () {
                const productCategoryText = $('#productCategoryText');

                if ($(this).is(':checked') && $(this).val() === 'Others') {
                    productCategoryText.removeAttr('readonly');
                } else {
                    productCategoryText.attr('readonly', true).val('');
                }
            });

            // Mengatur readonly text pada input channel
            $('input[name="channel"]').on('change', function () {
                const channelText = $('#country');
                if ($(this).is(':checked') && $(this).val() === 'International') {
                    channelText.removeAttr('readonly');
                } else {
                    channelText.attr('readonly', true).val('');
                }
            });

            // mengatur readonly text pada input certification
            $('input[name="certification"]').on('change', function () {
                const certificationText = $('#certificationText');

                if ($(this).is(':checked') && $(this).val() === 'Others') {
                    certificationText.removeAttr('readonly');
                } else {
                    certificationText.attr('readonly', true).val('');
                }
            });


            // mengatur readonly text pada input packaging
            $('input[name="packaging"]').on('change', function () {
                const existingPackagingText = $('#ExistingPackagingText');
                const newPackagingText = $('#NewPackagingText');

                if ($(this).is(':checked') && $(this).val() === 'Existing') {
                    existingPackagingText.removeAttr('readonly');
                    newPackagingText.attr('readonly', true).val('');
                } else {
                    newPackagingText.removeAttr('readonly');
                    existingPackagingText.attr('readonly', true).val('');
                }
            });

        };
        radiosOnChanges();

        document.addEventListener('DOMContentLoaded', function() {
            const no_reg = @json($no_reg);
            // mengambil list approver
            //$.ajax({
            //    url: '{{ route('mpdr.initiator.list.data') }}',
            //    method: 'GET',
            //    success: function(response) {
            //        response.forEach(item => {
            //            $('#initiator').append($('<option>', {
            //                value: item.nik, 
            //                text: item.name  
            //            }));
            //        });
            //    },
            //    error: function() {
            //        // Jika gagal, tampilkan pesan error
            //        console.log('Error ketika mengambil approver list');
            //        // $('#formData').html('<p>There was an error fetching the data.</p>');
            //    }
            //});
            
            // fetch data form
            $.ajax({
                url: '{{ route('mpdr.form.data') }}', // URL ke controller
                method: 'GET',
                data: {
                    no_reg: no_reg
                },
                success: function(response) {
                    console.log(response);
                    // No_Reg
                    $('#no_reg_text').text(no_reg);
                    $('#no_reg').val(no_reg);
                    
                    // Revision
                    $('#revision-no').text(response.revision.no);
                    $('#revision-count').text(response.revision.revision);
                    $('#revision-date').text(response.revision.date);
                    
                    $('#productName').val(response.product_name);
                    $('#levelPriority').val(response.level_priority);
                    if (response.initiator_detail && response.initiator_detail.initiator_name && response.initiator_detail.initiator_nik) {
                        $('#initiator_name_display').val(response.initiator_detail.initiator_name);
                        $('#initiator_nik_value').val(response.initiator_detail.initiator_nik);
                    }

                    $('#rationalForDevelopment').text(response.detail.rational_for_development);
                    $('#targetLaunch').val(response.detail.target_launch);

                    $(`[id="${response.category.category}"]`).prop('checked', true).prop('disabled', false).trigger('change');
                    $('#productCategoryText').val(response.category.other);

                    $(`#${response.channel.category}`).prop('checked', true).prop('disabled', false).trigger('change');
                    $('#country').val(response.channel.country);


                    tinymce.get('productDescription').setContent(response.description.product_description);
                    tinymce.get('productDescription').save();
                    $('#usageDescription').val(response.description.usage_description);
                    $('#storageTemperature').val(response.description.storage_temperature);
                    $('#deliveryTemperature').val(response.description.delivery_temperature);
                    
                    var category = response.certification.category === 'Others' ? 'certificationOthers' : response.certification.category;
                    $(`#${category}`).prop('checked', true).prop('disabled', false).trigger('change');
                    $('#certificationText').val(response.certification.other);

                    // Competitor's Product
                    response.competitor.forEach(function(value, index) {
                        $(`#productName${index+1}`).val(response.competitor[index].name);
                        $(`#size${index+1}`).val(response.competitor[index].size);
                        $(`#packaging${index+1}`).val(response.competitor[index].packaging);
                        $(`#priceIndication${index+1}`).val(response.competitor[index].price);
                    });

                    // Detailed Packaging
                    $('#weightProduct').val(response.packaging.weight);
                    $(`#${response.packaging.category}Packaging`).prop('checked', true).prop('disabled', false).trigger('change');
                    $(`#${response.packaging.category}PackagingText`).val(response.packaging.detail);
                    $('#productVariation').val(response.packaging.product_variation);

                    
                    $('#potentialVolume').val(response.market.potential_volume);
                    $('#expectedMargin').val(response.market.expected_margin);
                    $('#priceEstimate').val(response.market.price_estimate);
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data form');
                    // $('#formData').html('<p>There was an error fetching the data.</p>');
                }
            });
        });
    </script>
@endpush
</x-app-layout>
