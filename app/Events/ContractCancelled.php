<?php

namespace App\Events;

use App\Models\Contract;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ContractCancelled
{
    use Dispatchable, InteractsWithQueue;

    public Contract $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }
}
