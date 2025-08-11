@extends('layout.edit')
@section('content_title')
    <li><a class="active" data-toggle="tab" href="#permission-tab" role="tab" id="permission-btn">权限配置</a></li>
    <li><a data-toggle="tab" href="#position-tab" role="tab" id="position-btn">岗位配置</a></li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active fade" id="permission-tab" aria-labelledby="permission-btn">
            <div class="col-12">
                @foreach($permissions->where('level',1) as $permission_1)
                    <div class="nav-tabs mt-3">
                        <div class="styled-checkbox">
                            <input type="checkbox" name="permission[]" id="permission{{$permission_1->id}}" value="{{$permission_1->id}}" @if($myPermissions!=''&&$myPermissions->contains($permission_1)) checked @endif>
                            <label class="sidebar-heading" for="permission{{$permission_1->id}}">{{$permission_1->description}}</label>
                        </div>
                        @foreach($permissions->where('pid',$permission_1->id) as $permission_2)
                            <div class="ml-5">
                                <div class="styled-checkbox">
                                    <input type="checkbox" name="permission[]" id="permission{{$permission_2->id}}" data-pid="permission{{$permission_2->pid}}" value="{{$permission_2->id}}" class="check-none" @if($myPermissions!=''&&$myPermissions->contains($permission_2)) checked @endif>
                                    <label class="sidebar-heading" for="permission{{$permission_2->id}}">{{$permission_2->description}}</label>
                                </div>
                                @foreach($permissions->where('pid',$permission_2->id) as $permission_3)
                                    <div class="ml-5">
                                        <div class="styled-checkbox">
                                            <input type="checkbox" name="permission[]" id="permission{{$permission_3->id}}" data-pid="permission{{$permission_3->pid}}" data-ppid="permission{{$permission_2->pid}}" value="{{$permission_3->id}}"
                                                   @if($myPermissions!=''&&$myPermissions->contains($permission_3)) checked @endif>
                                            <label class="sidebar-heading" for="permission{{$permission_3->id}}">{{$permission_3->description}}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            <div class="enter-message">
                <button class="btn btn-outline-primary ripple submit-edit">确 定</button>
                <button class="btn btn-outline-secondary ripple sidebar-close">取 消</button>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="position-tab" aria-labelledby="position-btn">
            <div class="col-12">
                @foreach($positions->where('level',1) as $position_1)
                    <div class="nav-tabs mt-3">
                        <div class="styled-checkbox">
                            <input type="checkbox" name="position[]" id="position{{$position_1->id}}" value="{{$position_1->id}}">
                            <label class="sidebar-heading" for="position{{$position_1->id}}">{{$position_1->name}}</label>
                        </div>
                        @foreach($positions->where('pid',$position_1->id) as $position_2)
                            <div class="ml-5">
                                <div class="styled-checkbox">
                                    <input type="checkbox" name="position[]" id="position{{$position_2->id}}" value="{{$position_2->id}}" data-pid="position{{$position_2->pid}}" class="check-none">
                                    <label class="sidebar-heading" for="position{{$position_2->id}}">{{$position_2->name}}</label>
                                </div>
                                @foreach($positions->where('pid',$position_2->id) as $position_3)
                                    <div class="ml-5">
                                        <div class="styled-checkbox">
                                            <input type="checkbox" name="position[]" id="position{{$position_3->id}}" value="{{$position_3->id}}" data-pid="position{{$position_3->pid}}" data-ppid="position{{$position_2->pid}}" class="check-none"
                                                   @if($myPositions!=''&&$myPositions->contains($position_3)) checked @endif>
                                            <label class="sidebar-heading" for="position{{$position_3->id}}">{{$position_3->name}}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            <div class="enter-message">
                <button class="btn btn-outline-primary ripple submit-edit">确 定</button>
                <button class="btn btn-outline-secondary ripple sidebar-close">取 消</button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.check-none').each(function (i) {
                if ($('[data-pid="' + $(this).data('pid') + '"]:checked').length === 0) {
                    $('#' + $(this).data('pid')).prop('checked', false);
                } else {
                    $('#' + $(this).data('pid')).prop('checked', true);
                }
                if ($('[data-pid="' + $(this).data('ppid') + '"]:checked').length === 0) {
                    $('#' + $(this).data('ppid')).prop('checked', false);
                } else {
                    $('#' + $(this).data('ppid')).prop('checked', true);
                }
            });
        });
        $('.styled-checkbox input').change(function () {
            $('[data-pid="' + $(this).attr('id') + '"]').prop('checked', this.checked);
            $('[data-ppid="' + $(this).attr('id') + '"]').prop('checked', this.checked);
            if ($('#' + $(this).data('pid') + ':checked').length === 0) {
                $('#' + $(this).data('pid')).prop('checked', true);
            }
            if ($('#' + $(this).data('ppid') + ':checked').length === 0) {
                $('#' + $(this).data('ppid')).prop('checked', true);
            }
        })
        $('.check-none').change(function () {
            if ($('[data-pid="' + $(this).data('pid') + '"]:checked').length === 0) {
                $('#' + $(this).data('pid')).prop('checked', false);
            } else {
                $('#' + $(this).data('pid')).prop('checked', true);
            }
            if ($('[data-pid="' + $(this).data('ppid') + '"]:checked').length === 0) {
                $('#' + $(this).data('ppid')).prop('checked', false);
            } else {
                $('#' + $(this).data('ppid')).prop('checked', true);
            }
        })
    </script>
@endsection
