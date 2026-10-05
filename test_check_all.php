<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if ($user) {
    auth()->login($user);
}

try {
    $pfCtrl = $app->make(App\Http\Controllers\Admin\PointFraicheurController::class);
    $req = Illuminate\Http\Request::create('/admin/points_fraicheur');
    $htmlIndex = $pfCtrl->index($req)->render();
    echo "Points Fraicheur Index: OK (" . strlen($htmlIndex) . " bytes)\n";

    $htmlCreate = $pfCtrl->create()->render();
    echo "Points Fraicheur Create: OK (" . strlen($htmlCreate) . " bytes)\n";

    $point = App\Models\PointFraicheur::first();
    if ($point) {
        $htmlEdit = $pfCtrl->edit($point)->render();
        echo "Points Fraicheur Edit: OK (" . strlen($htmlEdit) . " bytes)\n";
    }

    $reqAvis = Illuminate\Http\Request::create('/admin/avis-points');
    $htmlAvis = $pfCtrl->avisIndex($reqAvis)->render();
    echo "Avis Index: OK (" . strlen($htmlAvis) . " bytes)\n";

    echo "ALL VIEWS RENDERED SUCCESSFULLY!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
