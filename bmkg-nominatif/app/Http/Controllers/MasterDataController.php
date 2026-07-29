<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MasterDataRepositoryInterface;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    public function __construct(
        protected MasterDataRepositoryInterface $masterDataRepository
    ) {}

    public function index(): View
    {
        $genders            = $this->masterDataRepository->getGenders();
        $religions          = $this->masterDataRepository->getReligions();
        $maritalStatuses    = $this->masterDataRepository->getMaritalStatuses();
        $employmentStatuses = $this->masterDataRepository->getEmploymentStatuses();
        $educations         = $this->masterDataRepository->getEducations();
        $ranks              = $this->masterDataRepository->getRanks();
        $positions          = $this->masterDataRepository->getPositions();
        $workUnits          = $this->masterDataRepository->getWorkUnits();

        return view('master.index', compact(
            'genders', 'religions', 'maritalStatuses',
            'employmentStatuses', 'educations', 'ranks',
            'positions', 'workUnits'
        ));
    }
}
