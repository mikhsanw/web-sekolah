<div class="panel">
    <div class="panel-body">
        <div class="row g-4">

            <!-- Event Information -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">
                        Event Information
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="fw-semibold">Event</label>
                            <div>
                                <span class="badge bg-primary text-uppercase">
                                    {{ $data->event ?? 'updated' }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Description</label>
                            <div>{{ $data->description }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Tanggal</label>
                            <div>{{ $data->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Context -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">
                        Context
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="fw-semibold">Performed By</label>
                            <div>
                                {{ $data->causer->name ?? 'System' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Subject</label>
                            <div>
                                {{ class_basename($data->subject_type) ?? '-' }} <br>
                                <small class="text-muted">#{{ $data->subject_id }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Log Name</label>
                            <div>{{ $data->log_name ?? '-' }}</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Properties -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">
                        Activity Properties
                    </div>
                    <div class="card-body p-0">
                        <pre class="bg-dark text-success p-3 mb-0" style="font-size: 13px; max-height:400px; overflow:auto;">
{{ json_encode($data->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                    </pre>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
    .modal-lg {
        max-width: 1000px !important;
    }
</style>
<script>
    $('.submit-data').hide();
    $('.modal-title').html('<i class="fa fa-search"></i> Detail Data {!! $page->title !!}');
</script>
