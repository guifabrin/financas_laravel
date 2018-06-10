<div class="modal fade" id="model_account_{{$account->id}}" tabindex="-1" role="dialog" aria-labelledby="{{$account->id}}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="{{__('common.close')}}">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="{{$account->id}}">{{__('common.import')}} {{$account->description}}</h4>
      </div>
      <form action="account/{{$account->id}}/uploadOfx" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-body">
          <input type="file" name="ofx-file[]" multiple />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{__('common.close')}}</button>
          <button type="submit" class="btn btn-primary">{{__('common.import')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>