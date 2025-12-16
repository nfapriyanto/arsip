<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Gallery Arsip
            <small>Preview semua arsip</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Gallery Arsip</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Daftar Arsip</h3>
                        <div class="box-tools pull-right">
                            <form method="get" action="<?php echo base_url('admin/arsip/gallery') ?>" class="form-inline" style="display: inline-block;">
                                <div class="form-group">
                                    <select name="kategori_id" class="form-control" style="width: 200px;">
                                        <option value="">Semua Kategori</option>
                                        <?php foreach($list_kategori as $kat): ?>
                                            <option value="<?php echo $kat->id ?>" <?php echo ($selected_kategori == $kat->id) ? 'selected' : '' ?>>
                                                <?php echo $kat->nama ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari..." value="<?php echo htmlspecialchars($search_query) ?>" style="width: 200px;">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                                <a href="<?php echo base_url('admin/arsip/gallery') ?>" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</a>
                            </form>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if(empty($arsip)): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Tidak ada arsip ditemukan.
                            </div>
                        <?php else: ?>
                            <div class="row" id="gallery-container">
                                <?php foreach($arsip as $item): ?>
                                    <div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 20px;">
                                        <div class="thumbnail" style="border: 1px solid #ddd; border-radius: 4px; padding: 10px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                            <a href="<?php echo base_url('admin/arsip/view/' . $item->id) ?>" target="_blank" style="display: block; position: relative;">
                                                <div style="width: 100%; height: 300px; overflow: hidden; background: #f5f5f5; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                    <img src="<?php echo base_url('admin/arsip/thumbnail/' . $item->id) ?>" 
                                                         alt="<?php echo htmlspecialchars($item->no_berkas ? $item->no_berkas : 'Arsip #' . $item->id) ?>" 
                                                         style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjQwMCIgZmlsbD0iI2Y1ZjVmNSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTgiIGZpbGw9IiM5OTk5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5ObyBQcmV2aWV3PC90ZXh0Pjwvc3ZnPg=='">
                                                </div>
                                                <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; padding: 5px 10px; border-radius: 3px; font-size: 12px;">
                                                    <i class="fa fa-eye"></i> View
                                                </div>
                                            </a>
                                            <div class="caption" style="padding: 10px 0 0 0;">
                                                <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: bold; color: #333; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($item->no_berkas ? $item->no_berkas : 'Arsip #' . $item->id) ?>">
                                                    <?php echo htmlspecialchars($item->no_berkas ? $item->no_berkas : 'Arsip #' . $item->id) ?>
                                                </h4>
                                                <p style="margin: 0 0 5px 0; font-size: 12px; color: #666; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($item->kategori_nama) ?>">
                                                    <i class="fa fa-folder"></i> <?php echo htmlspecialchars($item->kategori_nama) ?>
                                                </p>
                                                <?php if($item->uraian_masalah_kegiatan): ?>
                                                    <p style="margin: 0; font-size: 11px; color: #999; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.4; max-height: 2.8em;" title="<?php echo htmlspecialchars($item->uraian_masalah_kegiatan) ?>">
                                                        <?php echo htmlspecialchars($item->uraian_masalah_kegiatan) ?>
                                                    </p>
                                                <?php endif; ?>
                                                <?php if($item->tahun): ?>
                                                    <p style="margin: 5px 0 0 0; font-size: 11px; color: #999;">
                                                        <i class="fa fa-calendar"></i> <?php echo $item->tahun ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="text-center" style="margin-top: 20px;">
                                <p class="text-muted">
                                    <i class="fa fa-info-circle"></i> Menampilkan <?php echo count($arsip) ?> arsip
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    #gallery-container .thumbnail {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    #gallery-container .thumbnail:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
    }
    
    #gallery-container .thumbnail a {
        text-decoration: none;
    }
    
    #gallery-container .thumbnail img {
        transition: transform 0.2s;
    }
    
    #gallery-container .thumbnail:hover img {
        transform: scale(1.05);
    }
</style>









