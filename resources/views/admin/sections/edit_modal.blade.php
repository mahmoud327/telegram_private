    <!-- edit -->
    <div class="modal fade" id="exampleModal2{{ $section->id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">edit section</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('sections.update', $section->id) }}" method="post" autocomplete="off">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}

                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="name"
                                value="{{ $section->name }}">
                            <br>

                            <select name="material_id" class="form-control">
                                @foreach ($materials as $material)
                                    <option {{ $material->id == $section->material_id ? 'selected' : '' }} value="{{ $material->id }}">

                                        {{ $material->name }}

                                    </option>
                                @endforeach
                            </select>
                        </div>





                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">تاكيد</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                </div>
                </form>
            </div>
        </div>
    </div>
