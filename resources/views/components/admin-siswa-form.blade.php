<div class="wrapper w-100 h-100 position-fixed z-index-2" style="top: 0px;left: 0px;">
    <div class="overlay w-100 h-100 position-absolute bg-dark " style="top: 0px;left: 0px;opacity: 10%;">

    </div>
    <div class="row h-100 justify-content-center pt-5 position-relative" style="z-index: 999;">
        <div class="col-5 pt-4">
            <div class="card ">
                <div class="card-header pt-4 pb-2 no-after d-flex justify-content-between align-items-center">
                    <h1 class="h2">Student Registration Form</h1>
                    <a href="#">Close</a>
                </div>
                <div class="card-body">
                    
                    <div class="d-flex justify-between w-100">
                        
                        <form action="" method="POST" class="col-8">
                            @csrf
                            <div class="form-group">
                                <label for="">Nama Lengkap</label>
                                <input type="text" name="" id="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">NISN</label>
                                <input type="text" name="" id="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Tahun</label>
                                <select name="" id="" class="form-control">
                                    <option value="">2025/2026</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="">Kelas</label>
                                <select name="" id="" class="form-control">
                                    <option value="">10-B</option>
                                </select>
                            </div>
                            <div class="form-group my-0">
                                <label for="">Lahir</label>
                                <div class="d-flex flex-wrap justify-content-between " style="gap: 1rem;font-size: 16px;">
                                    <div class="form-group flex-fill px-0">
                                        <label for="" class="font-weight-normal">Tempat</label>
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                    <div class="form-group flex-fill px-0">
                                        <label for="" class="font-weight-normal">Tanggal</label>
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Gender</label>
                                <div class="input-group d-flex" style="gap: 1rem;">
                                
                                    <div class="form-check">
                                        <input type="radio" name="" id="" class="form-check-input">
                                        <label for="" class="form-check-label">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="" id="" class="form-check-input">
                                        <label for="" class="form-check-label">Perempuan</label>
                                    </div>
                                
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <textarea name="" id="" cols="30" rows="6" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-lg btn-info w-100">Submit</button>
                        </form>
                        <div class="col-4">
                            <input type="file" name="" id="" class="form-control-file">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>