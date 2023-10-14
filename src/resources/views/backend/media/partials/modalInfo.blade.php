<div class="modal fade" id="paymentMethods{{ $media->uuid }}" tabindex="-1"
    aria-hidden="true">
    <div
        class="modal-dialog modal-lg modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="text-start">

                    <table class="table text-wrap">
                        <tr>
                            <td>Title</td>
                            <td>:</td>
                            <td>{{ $media->title }}</td>
                        </tr>
                        <tr>
                            <td>Created At</td>
                            <td>:</td>
                            <td>{{ $media->created_at }}</td>
                        </tr>
                        <tr>
                            <td>File</td>
                            <td>:</td>
                            <td>{{ asset('storage/' . $media->file) }}</td>
                        </tr>
                        <tr>
                            <td>Original Name</td>
                            <td>:</td>
                            <td>{{ $media->original_name }}</td>
                        </tr>
                        <tr>
                            <td>Mime Type</td>
                            <td>:</td>
                            <td>{{ $media->mime_type }}</td>
                        </tr>
                        <tr>
                            <td>File Size</td>
                            <td>:</td>
                            <td>{{ $media->file_size }} Kb</td>
                        </tr>
                        <tr>
                            <td>User</td>
                            <td>:</td>
                            <td>{{ $media->user->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>