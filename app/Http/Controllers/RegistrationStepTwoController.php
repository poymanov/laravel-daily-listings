<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegistrationStepTwo\UpdateRequest;
use App\Service\CityService;
use App\UseCase\Auth\RegistrationStepTwo\Update;
use Throwable;

class RegistrationStepTwoController extends Controller
{
    /** @var CityService */
    private CityService $cityService;

    /**
     * @param CityService $cityService
     */
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit()
    {
        $cities = $this->cityService->findAll();

        return view('auth.registration-step-two', compact('cities'));
    }

    /**
     * @param UpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request)
    {
        $userId = auth()->id();

        $command = new Update\Command(
            (int) $userId,
            (int) $request->get('city_id'),
            $request->get('address'),
            $request->get('phone')
        );

        try {
            $handler = new Update\Handler();
            $handler->handle($command);

            return redirect()->route('dashboard');
        } catch (Throwable $e) {
            return redirect()->back();
        }
    }
}
