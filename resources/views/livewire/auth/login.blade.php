<div>
    <div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="card-group d-block d-md-flex row">
                <div class="card col-md-7 p-4 mb-0">
                  <div class="card-body">
                    {{-- <h1 class="fw-bold"><span class="text-primary ">SIMPEL</span> - MRP</h1> --}}
                    <img width="300" height="100" src="{{ asset('assets/img/simpel.svg') }}">
                    <p class="text-body-secondary">Silahkan login ke Akun</p>
                    @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif
                    <form wire:submit.prevent="login">
                        <div class="input-group mb-3"><span class="input-group-text">
                            <svg class="icon">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                            </svg>
                        </span>
                          <input class="form-control" type="email" id="email" wire:model="email" placeholder="Email">
                           @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="input-group mb-4"><span class="input-group-text">
                            <svg class="icon">
                                <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                            </svg></span>
                          <input class="form-control" type="password" id="password" wire:model="password" placeholder="Password">
                          @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="row">
                          <div class="col-6">
                            <button type="submit" class="btn btn-primary px-4">Login</button>
                          </div>
                          <div class="col-6 text-end">
    <a href="{{ route('password.request') }}" class="btn btn-link px-0">Lupa password?</a>
                          </div>
                        </div>
                    </form>
                  </div>
                </div>
                <div class="card col-md-5 text-white bg-primary py-5">
                  <div class="card-body text-center">
                    <div>
                      {{-- <h2 class="fw-bold border">SIMPEL-MRP</h2> --}}
                      <img width="200" height="60" alt="CoreUI Logo" src="{{ asset('assets/img/simpel4.svg') }}">
                      <p><span class="fw-bold">SIMPEL-MRP</span> adalah sistem digital yang dirancang untuk meningkatkan efisiensi, transparansi, dan akuntabilitas dalam pelayanan publik yang diselenggarakan oleh Majelis Rakyat Papua (MRP)</p>
                      {{-- <button class="btn btn-lg btn-outline-light mt-3" type="button">Buat Akun!</button> --}}
                      <a class="btn btn-lg btn-outline-light mt-3" type="button" href="/register">Buat Akun!</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>