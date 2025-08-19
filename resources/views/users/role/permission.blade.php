@extends('layout.edit')
@section('content_title')
    <li class="nav-item">
        <button class="nav-link active" data-toggle="tab" data-target="#permission-tab" id="permission-btn">权限配置</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-toggle="tab" data-target="#position-tab" id="position-btn">岗位配置</button>
    </li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active" id="permission-tab" aria-labelledby="permission-btn">
            <div class="col-12 mt-3">
                @foreach($permissions as $permission_1)
                    <div class="mt-2">
                        <input type="checkbox" class="btn-check" name="permission[]" id="permission{{$permission_1->id}}" value="{{$permission_1->id}}" data-level="1" @if($myPermissions!=''&&$myPermissions->contains($permission_1)) checked @endif>
                        <label class="btn btn-sm" for="permission{{$permission_1->id}}">{{$permission_1->description}}</label><br>
                        @foreach($permission_1->children as $permission_2)
                            <div class="mt-2 ms-5">
                                <input type="checkbox" class="btn-check" name="permission[]" id="permission{{$permission_2->id}}" value="{{$permission_2->id}}" data-level="2" data-pid="permission{{$permission_1->id}}" @if($myPermissions!=''&&$myPermissions->contains($permission_2)) checked @endif>
                                <label class="btn btn-sm" for="permission{{$permission_2->id}}">{{$permission_2->description}}</label><br>
                                @foreach($permission_2->children as $permission_3)
                                    <div class="mt-2 ms-5">
                                        <input type="checkbox" class="btn-check" name="permission[]" id="permission{{$permission_3->id}}" value="{{$permission_3->id}}" data-level="3" data-pid="permission{{$permission_2->id}}" data-ppid="permission{{$permission_1->id}}"
                                               @if($myPermissions!=''&&$myPermissions->contains($permission_3)) checked @endif>
                                        <label class="btn btn-sm" for="permission{{$permission_3->id}}">{{$permission_3->description}}</label><br>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="position-tab" aria-labelledby="position-btn">
            <div class="col-12 mt-3">
                @foreach($positions as $position_1)
                    <div class="mt-2">
                        <input type="checkbox" class="btn-check" name="position[]" id="position{{$position_1->id}}" value="{{$position_1->id}}" data-level="1" @if($myPositions!=''&&$myPositions->contains($position_1)) checked @endif>
                        <label class="btn btn-sm" for="position{{$position_1->id}}">{{$position_1->name}}</label><br>
                        @foreach($position_1->children as $position_2)
                            <div class="mt-2 ms-5">
                                <input type="checkbox" class="btn-check" name="position[]" id="position{{$position_2->id}}" value="{{$position_2->id}}" data-level="2" data-pid="position{{$position_1->id}}" @if($myPositions!=''&&$myPositions->contains($position_2)) checked @endif>
                                <label class="btn btn-sm" for="position{{$position_2->id}}">{{$position_2->name}}</label><br>
                                @foreach($position_2->children as $position_3)
                                    <div class="mt-2 ms-5">
                                        <input type="checkbox" class="btn-check" name="position[]" id="position{{$position_3->id}}" value="{{$position_3->id}}" data-level="3" data-pid="position{{$position_2->id}}" data-ppid="position{{$position_1->id}}"
                                               @if($myPositions!=''&&$myPositions->contains($position_3)) checked @endif>
                                        <label class="btn btn-sm" for="position{{$position_3->id}}">{{$position_3->name}}</label><br>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        $('.tab-pane .btn-check').change(function () {
            let id = $(this).attr('id');
            let pid = $(this).data('pid');
            let ppid = $(this).data('ppid');
            let level = $(this).data('level');
            $('[data-pid="' + id + '"]').prop('checked', this.checked);
            $('[data-ppid="' + id + '"]').prop('checked', this.checked);
            if (this.checked) {
                $('#' + pid).prop('checked', this.checked);
                $('#' + ppid).prop('checked', this.checked);
            }
            if (level === 2 && $('[data-pid="' + pid + '"]:checked').length === 0) {
                $('#' + pid).prop('checked', false);
            }
        })
    </script>
@endsection
