<?php

namespace App\Models\Interfaces;

use App\Models\NewsBlock;

interface IRssToNewsBlockConvertable {
    public function makeConvert(array $kvarray): NewsBlock;
    public function getRequestData() : array;

    // public function geturl() : string;
    // public function getnewstag() : string;
    // public function getassocarray() : array;
}
