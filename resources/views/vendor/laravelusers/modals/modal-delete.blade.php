<div class="modal fade modal-danger" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel"
    aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @lang('laravelusers::modals.delete_user_title')
                </h5>
                <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    @lang('laravelusers::modals.delete_user_message')
                </p>
            </div>
            <div class="modal-footer">
                {!! Form::button(__('laravelusers::modals.delete_user_btn_cancel'), ['class' => 'btn btn-light pull-left', 'type' => 'button', 'data-dismiss' => 'modal']) !!}
                {!! Form::button(__('laravelusers::modals.delete_user_btn_confirm'), ['class' => 'btn btn-danger pull-right btn-flat', 'type' => 'button', 'id' => 'confirm']) !!}
            </div>
        </div>
    </div>
</div>
