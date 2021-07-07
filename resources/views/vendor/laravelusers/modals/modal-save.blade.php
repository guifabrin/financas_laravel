<div class="modal fade modal-success modal-save" id="confirmSave" role="dialog" aria-labelledby="confirmSaveLabel"
    aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @lang('laravelusers::modals.edit_user__modal_text_confirm_title')
                </h5>
                <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    @lang('laravelusers::modals.confirm_modal_title_text')
                </p>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fa fa-fw ' . __('laravelusers::modals.confirm_modal_button_cancel_icon') . '" aria-hidden="true"></i> ' . __('laravelusers::modals.confirm_modal_button_cancel_text'), ['class' => 'btn btn-outline pull-left btn-flat', 'type' => 'button', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('<i class="fa fa-fw ' . __('laravelusers::modals.confirm_modal_button_save_icon') . '" aria-hidden="true"></i> ' . __('laravelusers::modals.confirm_modal_button_save_text'), ['class' => 'btn btn-success pull-right btn-flat', 'type' => 'button', 'id' => 'confirm']) !!}
            </div>
        </div>
    </div>
</div>
