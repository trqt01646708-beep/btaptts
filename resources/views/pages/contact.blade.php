@extends('frontend.layouts.app')

@section('title', 'Liên hệ')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Liên hệ</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="mb-4 text-center">Liên hệ với chúng tôi</h2>
                        <p class="text-muted text-center mb-4">
                            Nếu bạn có bất kỳ câu hỏi nào, vui lòng điền vào form bên dưới và chúng tôi sẽ liên hệ lại với bạn sớm nhất có thể.
                        </p>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                       id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label">Nội dung <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="row mt-5">
                    <div class="col-md-4 text-center mb-4">
                        <div class="contact-info-item">
                            <div class="icon-circle bg-primary text-white mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map-marker-alt fa-lg"></i>
                            </div>
                            <h5>Địa chỉ</h5>
                            <p class="text-muted">123 Đường ABC, Quận 1<br>TP. Hồ Chí Minh</p>
                        </div>
                    </div>

                    <div class="col-md-4 text-center mb-4">
                        <div class="contact-info-item">
                            <div class="icon-circle bg-primary text-white mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-phone fa-lg"></i>
                            </div>
                            <h5>Điện thoại</h5>
                            <p class="text-muted">
                                <a href="tel:0123456789" class="text-decoration-none text-muted">0123 456 789</a>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 text-center mb-4">
                        <div class="contact-info-item">
                            <div class="icon-circle bg-primary text-white mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope fa-lg"></i>
                            </div>
                            <h5>Email</h5>
                            <p class="text-muted">
                                <a href="mailto:info@example.com" class="text-decoration-none text-muted">info@example.com</a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Google Map (Optional) -->
                <div class="card mt-4">
                    <div class="card-body p-0">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4984676698994!2d106.69276897570621!3d10.77264625906936!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f4b3330bcc9%3A0xb8ee72b6a7a7d54!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBLaG9hIGjhu8ljIFThu7Egbmhpw6puIFRQLkhDTQ!5e0!3m2!1svi!2s!4v1704612892745!5m2!1svi!2s" 
                                width="100%" 
                                height="400" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
