<?php

namespace App\Events;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ContractSigned
{
    use Dispatchable, InteractsWithQueue;

    public Contract $contract;

    public User $signer;

    public string $role;

    public function __construct(Contract $contract, User $signer, string $role)
    {
        $this->contract = $contract;
        $this->signer = $signer;
        $this->role = $role;
    }
}
