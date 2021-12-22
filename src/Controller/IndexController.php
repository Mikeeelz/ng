<?php

namespace App\Controller;

use App\Model\Brand;
use App\Model\Category;
use App\Model\User;
use App\Model\Consultant;
use App\Model\Product;

class IndexController
{
    public function index(): void
    {
        $user = $this->getUser();

        if ($user === null) {
            echo 'Отстань';
            return;
        }

        if ($user->partnerId !== null) {
            $partnerName = $user->getPartnerName();
            require_once __DIR__ . '/../../templates/result.php';
            return;
        }

        if (!isset($_GET['submit'])) {
            require_once __DIR__ . '/../../templates/index.php';
            return;
        }

        $user->choosePartner();

        $partnerName = $user->getPartnerName();
        require_once __DIR__ . '/../../templates/result.php';
    }

    private function getUser(): ?User
    {
        if (!isset($_GET['hash'])) {
            return null;
        }

        $hash = $_GET['hash'];
        return User::findByHash($hash);
    }
}
