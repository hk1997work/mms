@extends('layout.show')
@section('content_title')
    @if($certificate->valid)
        @if($certificate->position=='备用'||$certificate->position=='借用')
            <li class="nav-item">
                <button class="nav-link active" data-toggle="tab" data-target="#apply-tab" id="apply-btn">使用</button>
            </li>
        @else
            @if($spares->count()!=0)
                <li class="nav-item">
                    <button class="nav-link active" data-toggle="tab" data-target="#spare-tab" id="spare-btn">备用</button>
                </li>
            @endif
            <li class="nav-item">
                <button class="nav-link @if($spares->count()==0) active @endif" data-toggle="tab" data-target="#replace-tab" id="replace-btn">更新</button>
            </li>
        @endif
    @endif
    <li class="nav-item">
        <button class="nav-link @if($certificate->valid==0) active @endif" data-toggle="tab" data-target="#edit-tab" id="edit-btn">修改</button>
    </li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane @if($certificate->valid==0) show active @endif" id="edit-tab" aria-labelledby="edit-btn">
            <form action="" onsubmit="return false;">
                {{method_field("put")}}
                {{csrf_field()}}
                <div class="row">
                    <div class="col-6 div-position_id">
                        <input type="hidden" name="type" value="edit">
                        <input type="hidden" name="position_id" value="{{$certificate->position_id}}">
                        <div class="sidebar-heading mt-3 mb-2">岗位</div>
                        <input type="text" class="form-control" value="{{$certificate->position}}" readonly>
                    </div>
                    <div class="col-6 div-sn">
                        <div class="sidebar-heading mt-3 mb-2">序号</div>
                        <input type="text" name="sn" class="form-control" value="{{$certificate->sn}}" readonly>
                    </div>
                    <div class="col-6 div-tool_id">
                        <div class="sidebar-heading mt-3 mb-2">器具名称</div>
                        <input type="text" class="form-control" value="{{$certificate->instrument}}" readonly>
                        <input type="hidden" name="tool_id" value="{{$certificate->tool_id}}">
                    </div>
                    <div class="col-6 div-model">
                        <div class="sidebar-heading mt-3 mb-2">规格型号</div>
                        <input type="text" name="model" class="form-control" value="{{$certificate->model}}" readonly>
                    </div>
                    <div class="col-6 div-factory_id">
                        <div class="sidebar-heading mt-3 mb-2">生产厂家</div>
                        <input type="text" class="form-control" value="{{$certificate->factory}}" readonly>
                        <input type="hidden" name="factory_id" value="{{$certificate->factory_id}}">
                    </div>
                    <div class="col-6 div-limit">
                        <div class="sidebar-heading mt-3 mb-2">测量范围</div>
                        <input type="text" name="limit" class="form-control" value="{{$certificate->limit}}" readonly>
                    </div>
                    <div class="col-6 div-number_id">
                        <div class="sidebar-heading mt-3 mb-2">出厂编号</div>
                        <input type="text" class="form-control" value="{{$certificate->number}}" readonly>
                        <input type="hidden" name="number_id" value="{{$certificate->number_id}}">
                    </div>
                    <div class="col-6 div-accuracy">
                        <div class="sidebar-heading mt-3 mb-2">精确度</div>
                        <input type="text" name="accuracy" class="form-control" value="{{$certificate->accuracy}}" readonly>
                    </div>
                    <div class="col-6 div-department_id">
                        <div class="sidebar-heading mt-3 mb-2">检定部门</div>
                        <select name="department_id" class="form-control form-select">
                            <option value="" selected disabled>请选择...</option>
                            @foreach($departments as $department)
                                <option value="{{$department->id}}" @if($certificate->department_id==$department->id) selected @endif>{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 div-cycle_id">
                        <div class="sidebar-heading mt-3 mb-2">检定周期</div>
                        <input type="text" name="cycle_id" class="form-control" value="{{$certificate->cycle}}" readonly>
                    </div>
                    <div class="col-6 div-category_id">
                        <div class="sidebar-heading mt-3 mb-2">证书类型</div>
                        <select name="category_id" class="form-control form-select">
                            <option value="" selected disabled>请选择...</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" @if($certificate->category_id==$category->id) selected @endif>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 div-abc_id">
                        <div class="sidebar-heading mt-3 mb-2">ABC</div>
                        <input type="text" name="abc_id" class="form-control" value="{{$certificate->abc}}" readonly>
                    </div>
                    <div class="col-6 div-verification_date">
                        <div class="sidebar-heading mt-3 mb-2">检定日期</div>
                        <input type="text" name="verification_date" class="form-control" value="{{$certificate->verification_date}}">
                    </div>
                    <div class="col-6 div-validity_date">
                        <div class="sidebar-heading mt-3 mb-2">有效期</div>
                        <input type="text" name="validity_date" class="form-control" value="{{$certificate->validity_date}}" readonly>
                    </div>
                    <div class="col-6 div-certificate_no">
                        <div class="sidebar-heading mt-3 mb-2">统一编号</div>
                        <input type="text" name="certificate_no" class="form-control" value="{{$certificate->certificate_no}}">
                    </div>
                    <div class="col-6 div-start">
                        <div class="sidebar-heading mt-3 mb-2">启用时间</div>
                        <input type="text" name="start" class="form-control" value="{{$certificate->start}}" readonly>
                    </div>
                    <div class="col-6 div-standard_id">
                        <div class="sidebar-heading mt-3 mb-2">检定标准</div>
                        <select name="standard_id[]" class="form-control form-select" data-live-search="true" multiple>
                            @foreach($standards as $standard)
                                <option value="{{$standard->id}}" @if($certificate->standards->contains($standard->id)) selected @endif>{{$standard->name2}}{{Str::limit($standard->name1,35)}}-</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 div-times">
                        <div class="sidebar-heading mt-3 mb-2">检定次数</div>
                        <input type="text" name="times" class="form-control" value="{{$certificate->times}}" readonly>
                    </div>
                    <div class="col-6 div-certificate_name">
                        <div class="sidebar-heading mt-3 mb-2">证书名称</div>
                        <input type="text" name="certificate_name" class="form-control" value="{{$certificate->certificate_name}}">
                    </div>
                    <div class="col-6 div-plan_id">
                        <div class="sidebar-heading mt-3 mb-2">检定计划</div>
                        <input type="text" name="plan_id" class="form-control" value="{{$certificate->plan}}" readonly>
                    </div>
                    <div class="col-6 div-remark">
                        <div class="sidebar-heading mt-3 mb-2">备注</div>
                        <input type="text" name="remark" class="form-control" value="{{$certificate->remark}}">
                    </div>
                    <div class="col-6">
                        <div class="sidebar-heading mt-3 mb-2">上传证书</div>
                        <input type="file" class="btn btn-secondary btn-square btn-sm" name="file_certificate" accept="application/pdf">
                    </div>
                </div>
                <div class="position-fixed bottom-0 end-0 p-3">
                    <button class="btn btn-outline-primary submit-edit sidebar-url">确 定</button>
                    <button class="btn btn-outline-secondary sidebar-close">取 消</button>
                </div>
            </form>
        </div>
        @if($certificate->valid)
            @if($certificate->position=='备用'||$certificate->position=='借用')
                <div role="tabpanel" class="tab-pane show active" id="apply-tab" aria-labelledby="apply-btn">
                    <form action="" onsubmit="return false;">
                        {{method_field("put")}}
                        {{csrf_field()}}
                        <div class="row">
                            <input type="hidden" name="type" value="apply">
                            <div class="col-6 div-position_id">
                                <div class="sidebar-heading mt-3 mb-2">岗位</div>
                                <select name="position_id" class="form-control form-select">
                                    <option value="" selected disabled>请选择...</option>
                                    @foreach($positions as $p1)
                                        @foreach($p1->children as $p2)
                                            <optgroup label="{{$p2->name}}">
                                                @foreach($p2->children as $p3)
                                                    <option value="{{$p3->id}}">{{$p3->name}}-{{$p3->code}}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 div-sn">
                                <div class="sidebar-heading mt-3 mb-2">序号</div>
                                <input type="text" name="sn" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-tool_id">
                                <div class="sidebar-heading mt-3 mb-2">器具名称</div>
                                <input type="text" class="form-control" value="{{$certificate->instrument}}" readonly>
                                <input type="hidden" name="tool_id" value="{{$certificate->tool_id}}">
                            </div>
                            <div class="col-6 div-model">
                                <div class="sidebar-heading mt-3 mb-2">规格型号</div>
                                <input type="text" name="model" class="form-control" value="{{$certificate->model}}" readonly>
                            </div>
                            <div class="col-6 div-factory_id">
                                <div class="sidebar-heading mt-3 mb-2">生产厂家</div>
                                <input type="text" class="form-control" value="{{$certificate->factory}}" readonly>
                                <input type="hidden" name="factory_id" value="{{$certificate->factory_id}}">
                            </div>
                            <div class="col-6 div-limit">
                                <div class="sidebar-heading mt-3 mb-2">测量范围</div>
                                <input type="text" name="limit" class="form-control" value="{{$certificate->limit}}" readonly>
                            </div>
                            <div class="col-6 div-number_id">
                                <div class="sidebar-heading mt-3 mb-2">出厂编号</div>
                                <input type="text" class="form-control" value="{{$certificate->number}}" readonly>
                                <input type="hidden" name="number_id" value="{{$certificate->number_id}}">
                            </div>
                            <div class="col-6 div-accuracy">
                                <div class="sidebar-heading mt-3 mb-2">精确度</div>
                                <input type="text" name="accuracy" class="form-control" value="{{$certificate->accuracy}}" readonly>
                            </div>
                            <div class="col-6 div-department_id">
                                <div class="sidebar-heading mt-3 mb-2">检定部门</div>
                                <input type="text" class="form-control" value="{{$certificate->department}}" readonly>
                                <input type="hidden" name="department_id" value="{{$certificate->department_id}}">
                            </div>
                            <div class="col-6 div-cycle_id">
                                <div class="sidebar-heading mt-3 mb-2">检定周期</div>
                                <input type="text" name="cycle_id" class="form-control" value="{{$certificate->cycle}}" readonly>
                            </div>
                            <div class="col-6 div-category_id">
                                <div class="sidebar-heading mt-3 mb-2">证书类型</div>
                                <input type="text" class="form-control" value="{{$certificate->category}}" readonly>
                                <input type="hidden" name="category_id" value="{{$certificate->category_id}}">
                            </div>
                            <div class="col-6 div-abc_id">
                                <div class="sidebar-heading mt-3 mb-2">ABC</div>
                                <input type="text" name="abc_id" class="form-control" value="{{$certificate->abc}}" readonly>
                            </div>
                            <div class="col-6 div-verification_date">
                                <div class="sidebar-heading mt-3 mb-2">检定日期</div>
                                <input type="text" name="verification_date" class="form-control" value="{{$certificate->verification_date}}" readonly>
                            </div>
                            <div class="col-6 div-validity_date">
                                <div class="sidebar-heading mt-3 mb-2">有效期</div>
                                <input type="text" name="validity_date" class="form-control" value="{{$certificate->validity_date}}" readonly>
                            </div>
                            <div class="col-6 div-certificate_no">
                                <div class="sidebar-heading mt-3 mb-2">统一编号</div>
                                <input type="text" name="certificate_no" class="form-control" value="{{$certificate->certificate_no}}" readonly>
                            </div>
                            <div class="col-6 div-start">
                                <div class="sidebar-heading mt-3 mb-2">启用时间</div>
                                <input type="text" name="start" class="form-control" value="{{$certificate->start}}" readonly>
                            </div>
                            <div class="col-6 div-standard_id">
                                <div class="sidebar-heading mt-3 mb-2">检定标准</div>
                                <input type="text" class="form-control" value="{{$certificate->standard}}" readonly>
                                @foreach($certificate->standards as $standard)
                                    <input type="hidden" name="standard_id[]" value="{{$standard->id}}">
                                @endforeach
                            </div>
                            <div class="col-6 div-times">
                                <div class="sidebar-heading mt-3 mb-2">检定次数</div>
                                <input type="text" name="times" class="form-control" value="{{$certificate->times}}" readonly>
                            </div>
                            <div class="col-6 div-certificate_name">
                                <div class="sidebar-heading mt-3 mb-2">证书名称</div>
                                <input type="text" name="certificate_name" class="form-control" value="{{$certificate->certificate_name}}" readonly>
                            </div>
                            <div class="col-6 div-plan_id">
                                <div class="sidebar-heading mt-3 mb-2">检定计划</div>
                                <input type="text" name="plan_id" class="form-control" value="{{$certificate->plan}}" readonly>
                            </div>
                            <div class="col-6 div-remark">
                                <div class="sidebar-heading mt-3 mb-2">备注</div>
                                <input type="text" name="remark" class="form-control" value="{{$certificate->remark}}">
                            </div>
                        </div>
                        <div class="position-fixed bottom-0 end-0 p-3">
                            <button class="btn btn-outline-primary submit-edit sidebar-url">确 定</button>
                            <button class="btn btn-outline-secondary sidebar-close">取 消</button>
                        </div>
                    </form>
                </div>
            @else
                <div role="tabpanel" class="tab-pane @if($spares->count()==0) show active @endif" id="replace-tab" aria-labelledby="replace-btn">
                    <form action="" onsubmit="return false;">
                        {{method_field("put")}}
                        {{csrf_field()}}
                        <div class="row">
                            <input type="hidden" name="type" value="replace">
                            <input type="hidden" name="id" value="{{$certificate->id}}">
                            <div class="col-3 div-cause">
                                <div class="sidebar-heading mt-3 mb-2">更换原因</div>
                                <select name="cause" class="form-control form-select">
                                    <option value="待检">更换</option>
                                    <option value="损坏">损坏</option>
                                </select>
                            </div>
                            <div class="col-3 div-position_id">
                                <div class="sidebar-heading mt-3 mb-2">岗位</div>
                                <input type="text" class="form-control" value="{{$certificate->position}}" readonly>
                                <input type="hidden" name="position_id" value="{{$certificate->position_id}}">
                            </div>
                            <div class="col-6 div-sn">
                                <div class="sidebar-heading mt-3 mb-2">序号</div>
                                <input type="text" name="sn" class="form-control" value="{{$certificate->sn}}" readonly>
                            </div>
                            <div class="col-6 div-tool_id">
                                <div class="sidebar-heading mt-3 mb-2">器具名称</div>
                                <select name="tool_id" class="form-control form-select" data-live-search="true">
                                    <option title="请选择..." value="" selected disabled>请选择...</option>
                                    @foreach($tools as $tool)
                                        <option title="{{$tool->instrument}}" value="{{$tool->id}}">{{$tool->instrument}}--{{$tool->model}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 div-model">
                                <div class="sidebar-heading mt-3 mb-2">规格型号</div>
                                <input type="text" name="model" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-factory_id">
                                <div class="sidebar-heading mt-3 mb-2">生产厂家</div>
                                <div class="input-group">
                                    <select name="factory_id" class="form-control form-select" disabled>
                                        <option value="" selected disabled>请选择...</option>
                                    </select>
                                    <span class="input-group-addon addon-primary btn-add factory_add" data-pos='right' data-menu='factory' data-cb="tool_id" hidden>增加</span>
                                </div>
                            </div>
                            <div class="col-6 div-limit">
                                <div class="sidebar-heading mt-3 mb-2">测量范围</div>
                                <input type="text" name="limit" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-number_id">
                                <div class="sidebar-heading mt-3 mb-2">出厂编号</div>
                                <div class="input-group">
                                    <select name="number_id" class="form-control form-select" disabled>
                                        <option value="" selected disabled>请选择...</option>
                                    </select>
                                    <span class="input-group-addon addon-primary btn-add number_add" data-pos='right' data-menu='number' data-cb="factory_id" hidden>增加</span>
                                    <input type="hidden" name="number" value="{{$certificate->number_id}}">
                                </div>
                            </div>
                            <div class="col-6 div-accuracy">
                                <div class="sidebar-heading mt-3 mb-2">精确度</div>
                                <input type="text" name="accuracy" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-department_id">
                                <div class="sidebar-heading mt-3 mb-2">检定部门</div>
                                <select name="department_id" class="form-control form-select">
                                    <option value="" selected disabled>请选择...</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 div-cycle_id">
                                <div class="sidebar-heading mt-3 mb-2">检定周期</div>
                                <input type="text" name="cycle_id" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-category_id">
                                <div class="sidebar-heading mt-3 mb-2">证书类型</div>
                                <select name="category_id" class="form-control form-select">
                                    <option value="" selected disabled>请选择...</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 div-abc_id">
                                <div class="sidebar-heading mt-3 mb-2">ABC</div>
                                <input type="text" name="abc_id" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-verification_date">
                                <div class="sidebar-heading mt-3 mb-2">检定日期</div>
                                <input type="text" name="verification_date" class="form-control">
                            </div>
                            <div class="col-6 div-validity_date">
                                <div class="sidebar-heading mt-3 mb-2">有效期</div>
                                <input type="text" name="validity_date" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-certificate_no">
                                <div class="sidebar-heading mt-3 mb-2">统一编号</div>
                                <input type="text" name="certificate_no" class="form-control">
                            </div>
                            <div class="col-6 div-start">
                                <div class="sidebar-heading mt-3 mb-2">启用时间</div>
                                <input type="text" name="start" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-standard_id">
                                <div class="sidebar-heading mt-3 mb-2">检定标准</div>
                                <select name="standard_id[]" class="form-control form-select" data-live-search="true" multiple>
                                    @foreach($standards as $standard)
                                        <option value="{{$standard->id}}">{{$standard->name1}}-{{Str::limit($standard->name2,35)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 div-times">
                                <div class="sidebar-heading mt-3 mb-2">检定次数</div>
                                <input type="text" name="times" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-certificate_name">
                                <div class="sidebar-heading mt-3 mb-2">证书名称</div>
                                <input type="text" name="certificate_name" class="form-control">
                            </div>
                            <div class="col-6 div-plan_id">
                                <div class="sidebar-heading mt-3 mb-2">检定计划</div>
                                <input type="text" name="plan_id" class="form-control" readonly>
                            </div>
                            <div class="col-6 div-remark">
                                <div class="sidebar-heading mt-3 mb-2">备注</div>
                                <input type="text" name="remark" class="form-control" value="{{$certificate->remark}}">
                            </div>
                            <div class="col-6">
                                <div class="sidebar-heading mt-3 mb-2">上传证书</div>
                                <input type="file" class="btn btn-secondary btn-square btn-sm" name="file_certificate" accept="application/pdf">
                            </div>
                        </div>
                        <div class="position-fixed bottom-0 end-0 p-3">
                            <button class="btn btn-outline-primary submit-edit sidebar-url">确 定</button>
                            <button class="btn btn-outline-secondary sidebar-close">取 消</button>
                        </div>
                    </form>
                </div>
                @if($spares->count()!=0)
                    <div role="tabpanel" class="tab-pane show active" id="spare-tab" aria-labelledby="spare-btn">
                        <div class="col-12">
                            <table id="off-sidebar-table" class="table table-hover table-data">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>器具名称</th>
                                    <th>规格型号</th>
                                    <th>出厂编号</th>
                                    <th>检定日期</th>
                                    <th>有效期</th>
                                    <th>检定部门</th>
                                    <th>备注</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($spares as $spare)
                                    <tr @if($spare->valid == 1 && $spare->validity_date < date("Y-m-d")) class="text-danger" @endif>
                                        <td>{{$spare->order}}</td>
                                        <td>{{$spare->instrument}}</td>
                                        <td>{{$spare->model}}</td>
                                        <td>{{$spare->number}}</td>
                                        <td>{{$spare->verification_date}}</td>
                                        <td>{{$spare->validity_date}}</td>
                                        <td>{{$spare->department}}</td>
                                        <td>{{$spare->remark}}</td>
                                        <td>
                                            <div class="d-flex">
                                                <form action="" onsubmit="return false;">
                                                    {{method_field("put")}}
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="type" value="spare">
                                                    <input type="hidden" name="id" value="{{$certificate->id}}">
                                                    <input type="hidden" name="cause" value="待检">
                                                    <button class="btn btn-outline-secondary btn-sm submit-edit" data-id="{{$spare->id}}">更换</button>
                                                </form>
                                                <form action="" onsubmit="return false;">
                                                    {{method_field("put")}}
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="type" value="spare">
                                                    <input type="hidden" name="id" value="{{$certificate->id}}">
                                                    <input type="hidden" name="cause" value="损坏">
                                                    <button class="btn btn-outline-secondary btn-sm submit-edit mx-1" data-id="{{$spare->id}}">损坏</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="position-fixed bottom-0 end-0 p-3">
                            <button class="btn btn-outline-secondary sidebar-close">取 消</button>
                        </div>
                    </div>
                @endif
            @endif
        @endif
    </div>
    <script>
        $('[name="tool_id"],[name="standard_id[]"]').selectpicker();
        $('[name="verification_date"]:not(:read-only)').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            parentEl: $('.off-sidebar-container'),
            container: '[name="verification_date"]',
        });
    </script>
@endsection
