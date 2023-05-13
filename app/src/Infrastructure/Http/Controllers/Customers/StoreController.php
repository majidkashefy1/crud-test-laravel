<?php

namespace Ddd\Infrastructure\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Ddd\Application\Customers\Command\CreateCustomerCommand;
use Ddd\Application\Customers\Handler\CreateCustomerHandler;
use Ddd\Application\Customers\Requests\CreateCustomerRequest;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{

    public function __construct(private CreateCustomerHandler $createCustomerHandler)
    {
    }

    /**
     * @param CreateCustomerRequest $request
     * @return RedirectResponse
     */
    public function store(CreateCustomerRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $command = new CreateCustomerCommand(
                $request->first_name,
                $request->last_name,
                $request->email,
                $request->bank_account_number,
                $request->phone_number,
                $request->date_of_birth
            );
            $this->createCustomerHandler->handle($command);

            return redirect()->route('customers.create')
                ->with('success', __('The customer has been created.'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

}