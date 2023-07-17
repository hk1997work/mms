@extends('layout.edit')
@section('text_modal-title','角色权限')

@section('content_form')
    <div class="table-responsive" style="max-height:500px;">
        @foreach($permissions->where('level',1) as $permission_1)
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3 row d-flex align-items-center" style="border-bottom: 1px solid;">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 styled-checkbox">
                    <input type="checkbox" name="permission[]" id="permission{{$permission_1->id}}" value="{{$permission_1->id}}" @if($myPermissions->contains($permission_1)) checked @endif>
                    <label for="permission{{$permission_1->id}}">{{$permission_1->description}}</label>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                    @foreach($permissions->where('pid',$permission_1->id) as $permission_2)
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3 mt-3 row d-flex align-items-center" @if(!$loop->last) style="border-bottom: 1px solid;" @endif>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 styled-checkbox">
                                <input type="checkbox" name="permission[]" id="permission{{$permission_2->id}}" value="{{$permission_2->id}}" @if($myPermissions->contains($permission_2)) checked @endif>
                                <label for="permission{{$permission_2->id}}">{{$permission_2->description}}</label>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                @foreach($permissions->where('pid',$permission_2->id) as $permission_3)
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 row styled-checkbox">
                                        <input type="checkbox" name="permission[]" id="permission{{$permission_3->id}}" value="{{$permission_3->id}}" @if($myPermissions->contains($permission_3)) checked @endif>
                                        <label for="permission{{$permission_3->id}}">{{$permission_3->description}}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        @foreach($positions->where('level',1) as $position_1)
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3 row d-flex align-items-center" style="border-bottom: 1px solid;">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 styled-checkbox">
                    <input type="checkbox" name="position[]" id="position{{$position_1->id}}" value="{{$position_1->id}}" @if($myPositions->contains($position_1)) checked @endif>
                    <label for="position{{$position_1->id}}">{{$position_1->name}}</label>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                    @foreach($positions->where('pid',$position_1->id) as $position_2)
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3 mt-3 row d-flex align-items-center" @if(!$loop->last) style="border-bottom: 1px solid;" @endif>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 styled-checkbox">
                                <input type="checkbox" name="position[]" id="position{{$position_2->id}}" value="{{$position_2->id}}" @if($myPositions->contains($position_2)) checked @endif>
                                <label for="position{{$position_2->id}}">{{$position_2->name}}</label>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                @foreach($positions->where('pid',$position_2->id) as $position_3)
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 row styled-checkbox">
                                        <input type="checkbox" name="position[]" id="position{{$position_3->id}}" value="{{$position_3->id}}" @if($myPositions->contains($position_3)) checked @endif>
                                        <label for="position{{$position_3->id}}">{{$position_3->name}}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <script>
        $("input[type='checkbox']").on('change', function () {
            if ($(this).prop("checked")) {
                $(this).closest('.row').parent().closest('.row').children(".styled-checkbox").children("input[type='checkbox']").closest('.row').parent().closest('.row').children(".styled-checkbox").children("input[type='checkbox']").prop("checked", true)
                $(this).closest('.row').parent().closest('.row').children(".styled-checkbox").children("input[type='checkbox']").prop("checked", true)
                $(this).closest('.row').find("input[type='checkbox']").prop("checked", true)
            } else {
                $(this).closest('.row').find("input[type='checkbox']").prop("checked", false)
            }
        })
    </script>
@endsection
