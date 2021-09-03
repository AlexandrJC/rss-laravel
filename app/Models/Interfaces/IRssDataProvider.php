<?php

namespace App\Models\Interfaces;

interface IRssDataProvider {
    public function getParserData(): array;
}
