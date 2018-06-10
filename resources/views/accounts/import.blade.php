<div class="modal fade" id="model_account_{{$id}}" tabindex="-1" role="dialog" aria-labelledby="{{$id}}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="{{__('common.close')}}">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="{{$id}}">{{__('common.import')}} {{$account->description}}</h4>
      </div>
      <form action="/account/{{!$isAccount?'invoice/':''}}uploadOfx" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" value="{{$id}}" name="accountId"/>
        <div class="modal-body">
          <input type="file" name="ofx-file[]" multiple />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{__('common.close')}}</button>
          <button type="submit" class="btn btn-primary">{{__('common.import')}}</button>
        </div>
      </form>
      <form action="/account/{{!$isAccount?'invoice/':''}}uploadCsv" method="POST" enctype="multipart/form-data">
        @if ($isAccount)
          <input type="hidden" value="{{$id}}" name="accountId"/>
        @else
          <input type="hidden" value="{{$accountId}}" name="accountId"/>
          <input type="hidden" value="{{$id}}" name="invoiceId"/>
        @endif
        {{csrf_field()}}
        <div class="modal-body">
          <input type="file" name="csv-file[]" multiple />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{__('common.close')}}</button>
          <button type="submit" class="btn btn-primary">{{__('common.import')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>