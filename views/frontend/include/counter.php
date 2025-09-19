    <?php
    require_once 'model/AdminModel.php';
    $counterModel = new AdminModel($pdo);

    $counterSettings = $counterModel->getCounterSettings();

    $counters = [
        [

            'value' => $counterSettings['count_no_1'],
            'label' => $counterSettings['counter_name_1']
        ],
        [

            'value' => $counterSettings['count_no_2'],
            'label' => $counterSettings['counter_name_2']
        ],
        [

            'value' => $counterSettings['count_no_3'],
            'label' => $counterSettings['counter_name_3']
        ],
        [

            'value' => $counterSettings['count_no_4'],
            'label' => $counterSettings['counter_name_4']
        ],
    ];

    ?>
    <!-- Counter Section Start -->
    <section class="counter-section dark-bg mt-2">
        <div class="container">
            <div class="counter-wrapper">
                <div class="row g-4">

                    <?php foreach ($counters as $counter) { ?>
                        <div class="col-lg-3 col-md-6 col-sm-6 wow fadeInUp bg-dark" data-wow-delay=".3s">
                            <div class="counter-box-items bg-dark">
                                <h2><span class="count"><?= htmlspecialchars($counter['value']) ?></span>+</h2>
                                <p><?= htmlspecialchars($counter['label']) ?></p>
                            </div>
                        </div>
                    <?php } ?>


                </div>
            </div>
        </div>
    </section>