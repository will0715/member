<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\ChopExpiredSettingRepository;
use App\Helpers\CustomerHelper;
use App\Exceptions\ResourceNotFoundException;
use Cache;

class ChopExpiredSettingService
{

    public function __construct()
    {
        $this->chopExpiredSettingRepository = app(ChopExpiredSettingRepository::class);
    }

    public function listChopExpiredSettings($request)
    {
        $this->chopExpiredSettingRepository->pushCriteria(new RequestCriteria($request));
        $this->chopExpiredSettingRepository->pushCriteria(new LimitOffsetCriteria($request));
        $chopExpiredSettings = $this->chopExpiredSettingRepository->all();

        return $chopExpiredSettings;
    }

    public function findChopExpiredSetting($id)
    {
        $chopExpiredSetting = $this->chopExpiredSettingRepository->findWithoutFail($id);
        if (!$chopExpiredSetting) {
            throw new ResourceNotFoundException('Chop Expired Setting Not Found');
        }
        return $chopExpiredSetting;
    }

    public function newChopExpiredSetting($data)
    {
        $chopExpiredSetting = $this->chopExpiredSettingRepository->create($data);
        return $chopExpiredSetting;
    }

    public function updateChopExpiredSetting($data, $id)
    {
        $chopExpiredSetting = $this->chopExpiredSettingRepository->update($data, $id);
        return $chopExpiredSetting;
    }

    public function deleteChopExpiredSetting($id)
    {
        $chopExpiredSetting = $this->findChopExpiredSetting($id);
        $chopExpiredSetting->delete();
        return $chopExpiredSetting;
    }
}
