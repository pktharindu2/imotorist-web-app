<?php

namespace App\Models\Access\User\Traits\Attribute;

/**
 * Class UserAttribute.
 */
trait UserAttribute
{
    /**
     * @return mixed
     */
    public function canChangeEmail()
    {
        return config('access.users.change_email');
    }

    /**
     * @return bool
     */
    public function canChangePassword()
    {
        return ! app('session')->has(config('access.socialite_session_name'));
    }

    /**
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        if ($this->isActive()) {
            return "<label class='label label-success'>".trans('labels.general.active').'</label>';
        }

        return "<label class='label label-danger'>".trans('labels.general.inactive').'</label>';
    }

    /**
     * @return string
     */
    public function getConfirmedLabelAttribute()
    {
        if ($this->isConfirmed()) {
            return "<label class='label label-success'>".trans('labels.general.yes').'</label>';
        }

        return "<label class='label label-danger'>".trans('labels.general.no').'</label>';
    }

    /**
     * @return string
     */
    public function getVerifiedLabelAttribute()
    {
        if ($this->isVerified()) {
            return "<label class='label label-success'>".trans('labels.general.verified').'</label>';
        }

        return "<label class='label label-danger'>".trans('labels.general.unverified').'</label>';
    }

    /**
     * @return mixed
     */
    public function getPictureAttribute()
    {
        return $this->getPicture();
    }

    /**
     * @param bool $size
     *
     * @return mixed
     */
    public function getPicture($size = false)
    {
        if (! $size) {
            $size = config('gravatar.default.size');
        }

        return gravatar()->get($this->email, ['size' => $size]);
    }

    /**
     * @param $provider
     *
     * @return bool
     */
    public function hasProvider($provider)
    {
        foreach ($this->providers as $p) {
            if ($p->provider === $provider) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return 1 === $this->status;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return 1 === $this->confirmed;
    }

    /**
     * @return bool
     */
    public function isVerified()
    {
        return 1 === $this->verified;
    }

    /**
     * @return string
     */
    public function getShowButtonAttribute()
    {
        return '<a href="'.route('admin.access.user.show', $this).'" class="btn btn-xs btn-info"><i class="fa fa-search" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.view').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.access.user.edit', $this).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.edit').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getChangePasswordButtonAttribute()
    {
        return '<a href="'.route('admin.access.user.change-password', $this).'" class="btn btn-xs btn-info"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.change_password').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getStatusButtonAttribute()
    {
        if ($this->id !== access()->id()) {
            switch ($this->status) {
                case 0:
                    return '<a href="'.route('admin.access.user.mark', [
                        $this,
                        1,
                    ]).'" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.activate').'"></i></a> ';

                case 1:
                    return '<a href="'.route('admin.access.user.mark', [
                        $this,
                        0,
                    ]).'" class="btn btn-xs btn-warning"><i class="fa fa-pause" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.deactivate').'"></i></a> ';

                default:
                    return '';
            }
        }

        return '';
    }

    /**
     * @return string
     */
    public function getConfirmedButtonAttribute()
    {
        if (! $this->isConfirmed()) {
            return '<a href="'.route('admin.access.user.account.confirm.resend', $this).'" class="btn btn-xs btn-success"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title='.trans('buttons.backend.access.users.resend_email').'"></i></a> ';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        if ($this->id !== access()->id() && 1 !== $this->id) {
            return '<a href="'.route('admin.access.user.destroy', $this).'"
                 data-method="delete"
                 data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                 data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
                 data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
                 class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a> ';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getRestoreButtonAttribute()
    {
        return '<a href="'.route('admin.access.user.restore', $this).'" name="restore_user" class="btn btn-xs btn-info"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.restore_user').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getDeletePermanentlyButtonAttribute()
    {
        return '<a href="'.route('admin.access.user.delete-permanently', $this).'" name="delete_user_perm" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.delete_permanently').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getLoginAsButtonAttribute()
    {
        /*
         * If the admin is currently NOT spoofing a user
         */
        if (! session()->has('admin_user_id') || ! session()->has('temp_user_id')) {
            //Won't break, but don't let them "Login As" themselves
            if ($this->id !== access()->id()) {
                return '<a href="'.route('admin.access.user.login-as',
                    $this).'" class="btn btn-xs btn-success"><i class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.login_as',
                    ['user' => $this->full_name]).'"></i></a> ';
            }
        }

        return '';
    }

    /**
     * @return string
     */
    public function getClearSessionButtonAttribute()
    {
        if ($this->id !== access()->id() && 'database' === config('session.driver')) {
            return '<a href="'.route('admin.access.user.clear-session', $this).'"
			 	 data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                 data-trans-button-confirm="'.trans('buttons.general.continue').'"
                 data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
                 class="btn btn-xs btn-warning" name="confirm_item"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.clear_session').'"></i></a> ';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        if ($this->trashed()) {
            return $this->getRestoreButtonAttribute().
                $this->getDeletePermanentlyButtonAttribute();
        }

        return
            $this->getClearSessionButtonAttribute().
            $this->getLoginAsButtonAttribute().
            $this->getShowButtonAttribute().
            $this->getEditButtonAttribute().
            $this->getChangePasswordButtonAttribute().
            $this->getStatusButtonAttribute().
            $this->getConfirmedButtonAttribute().
            $this->getDeleteButtonAttribute();
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->other_names
             ? $this->surname.' '.$this->other_names
             : $this->surname;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /**
     * Find the user identified by the given $identifier.
     *
     * @param $identifier email|phone
     *
     * @return mixed
     */
    public function findForPassport($identifier)
    {
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        return $this->Where($field, $identifier)->first();
    }

    /**
     * @return int
     */
    public function countUnpaidTickets()
    {
        if ($this->motorist) {
            return \count($this->motorist->tickets->where('paid', '=', false));
        }

        return 0;
    }

    /**
     * @return int
     */
    public function countTickets()
    {
        if ($this->motorist) {
            return \count($this->motorist->tickets);
        }

        return 0;
    }
}
