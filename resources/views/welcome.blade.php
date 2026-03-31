<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
</head>
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f6f7fb;
    color: #1e293b;
    padding: 30px 60px;
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 60px;
}

.logo {
    font-weight: 600;
    font-size: 18px;
}

.logo span {
    margin-left: 5px;
}

.login a {
    text-decoration: none;
    color: #64748b;
    font-weight: 500;
}

/* Hero */
.hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 80px;
}

.hero-left {
    width: 50%;
}

.hero-left h1 {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 20px;
}

.hero-left p {
    color: #64748b;
    margin-bottom: 30px;
    line-height: 1.6;
}

.search-box input {
    width: 80%;
    padding: 14px 20px;
    border-radius: 30px;
    border: 1px solid #e2e8f0;
    margin-bottom: 20px;
    outline: none;
}

.btn-primary {
    padding: 12px 28px;
    border: none;
    border-radius: 8px;
    background: #2563eb;
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s;
}

.btn-primary:hover {
    background: #1e40af;
}
.hero-right {
    position: relative;
    width: 40%;
    height: 320px;
}
.card {
    position: absolute;
    width: 280px;
    height: 360px;
    border-radius: 28px;
    background: #dbe1ea;
    box-shadow: 0 30px 60px rgba(0,0,0,0.08);
}

.stack-1 {
    top: 0;
    left: 40px;
    overflow: hidden;
}

.stack-2 {
    top: 40px;
    left: 90px;
    overflow: hidden;
}
.stack-3 {
    top: 80px;
    left: 140px;
    overflow: hidden; 
}
.image-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;   
    object-position: center; 
}

/* Popular Section */
.popular h2 {
    font-size: 22px;
    margin-bottom: 25px;
}

.book-list {
    display: flex;
    gap: 50px;
}

.book-item {
    background: #ffffff;
    padding: 25px 45px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s;
    align-items: center;

}

.book-item:hover {
    transform: translateY(-5px);
}
</style>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            📚 <span>Perpustakaan Digital</span>
        </div>
        <div class="login">
            <a href="{{ route ('login')}}">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-left">
            <h1>Temukan Buku Terbaik <br>Untuk Masa Depanmu</h1>
            <p>
                Koleksi buku lengkap, sistem modern, dan pengalaman membaca yang
                lebih nyaman dan profesional.
            </p>

            <div class="search-box">
                <input type="text" placeholder="Cari buku, pengarang, kategori...">
            </div>

            <button class="btn-primary">Mulai Jelajahi</button>
        </div>

       <div class="hero-right">
    <div class="card stack-1 image-card">
         <img src="{{asset("images/programmer.jpg")}}"
         alt="Book">
    </div>
    <div class="card stack-2 image-card">
        <img src="{{asset("images/Ui.jpg")}}"
         alt="Book">
    </div>

    <div class="card stack-3 image-card">
        <img src="{{asset("images/2.jpg")}}"
         alt="Book">
    </div>
</div>
</div>
        </div>
    </section>

    <!-- Popular Books -->
    <section class="popular">
        <h2>Buku Populer</h2>

        <div class="book-list">
            <div class="book-item">Belajar Laravel Modern</div>
            <div class="book-item">Mastering MySQL</div>
            <div class="book-item">UI UX Design Expert</div>
            <div class="book-item">Pemrograman Web Profesional</div>
        </div>
    </section>

</body>
</html>