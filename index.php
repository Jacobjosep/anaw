<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANAW - Act of Networking and Welfare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #1a73e8;
            --secondary-blue: #4285f4;
            --light-blue: #e8f0fe;
            --dark-blue: #0d47a1;
            --text-dark: #202124;
            --text-light: #5f6368;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --border-color: #dadce0;
            --gradient-primary: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            --gradient-secondary: linear-gradient(135deg, #4285f4 0%, #1a73e8 100%);
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 8px 24px rgba(0, 0, 0, 0.12);
            --shadow-heavy: 0 12px 36px rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-gray);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background-color: var(--white);
            box-shadow: var(--shadow-light);
            position: sticky;
            top: 0;
            z-index: 100;
            transition: var(--transition);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo-img {
            height: 50px;
            width: auto;
            margin-right: 10px;
            border-radius: 8px;
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            letter-spacing: -0.5px;
        }
        
        .logo-text span {
            color: var(--dark-blue);
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 25px;
            position: relative;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: var(--transition);
            padding: 8px 0;
            position: relative;
            cursor: pointer;
        }
        
        nav ul li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-blue);
            transition: var(--transition);
        }
        
        nav ul li a:hover::after,
        nav ul li a.active::after {
            width: 100%;
        }
        
        nav ul li a:hover {
            color: var(--primary-blue);
        }
        
        .auth-buttons {
            display: flex;
            gap: 12px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            box-shadow: var(--shadow-light);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .btn-outline:hover {
            background-color: var(--light-blue);
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 100px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,0 Q500,80 1000,0 L1000,100 L0,100 Z" fill="rgba(255,255,255,0.1)"></path></svg>');
            background-size: cover;
            background-position: center;
            opacity: 0.2;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }
        
        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .btn-light {
            background-color: var(--white);
            color: var(--primary-blue);
            box-shadow: var(--shadow-light);
        }
        
        .btn-light:hover {
            background-color: var(--light-blue);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        /* Features Section */
        .features {
            padding: 100px 0;
            background-color: var(--white);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .section-title p {
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 40px 30px;
            text-align: center;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-primary);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-heavy);
        }
        
        .feature-icon {
            background: var(--gradient-secondary);
            color: var(--white);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2rem;
            box-shadow: var(--shadow-light);
        }
        
        .feature-card h3 {
            margin-bottom: 15px;
            color: var(--text-dark);
            font-size: 1.4rem;
        }
        
        .feature-card p {
            color: var(--text-light);
            line-height: 1.7;
        }
        
        /* Social Media Section */
        .social-media {
            padding: 80px 0;
            background-color: var(--light-gray);
            text-align: center;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        
        .social-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background-color: var(--white);
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
        }
        
        .social-icon:hover {
            background: var(--gradient-primary);
            color: var(--white);
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        
        /* Footer */
        footer {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 70px 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-column h3 {
            margin-bottom: 25px;
            font-size: 1.3rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 2px;
            background-color: rgba(255, 255, 255, 0.5);
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 12px;
        }
        
        .footer-column ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: inline-block;
        }
        
        .footer-column ul li a:hover {
            color: var(--white);
            transform: translateX(5px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Tab Content Styles */
        .tab-content {
            display: none;
            padding: 80px 0;
            animation: fadeIn 0.5s ease;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .about-content, .programs-content, .meetings-content, .donate-content, .contact-content {
            background-color: var(--white);
            padding: 60px 0;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }
        
        .content-card {
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
            padding: 30px;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
        }
        
        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        
        .content-card h3 {
            color: var(--primary-blue);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .meeting-list, .program-list {
            list-style: none;
        }
        
        .meeting-list li, .program-list li {
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .meeting-list li:last-child, .program-list li:last-child {
            border-bottom: none;
        }
        
        .meeting-date {
            font-weight: bold;
            color: var(--primary-blue);
        }
        
        .donation-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .donation-option {
            background: var(--light-gray);
            border-radius: var(--border-radius);
            padding: 25px;
            text-align: center;
            transition: var(--transition);
            border: 2px solid transparent;
        }
        
        .donation-option:hover {
            border-color: var(--primary-blue);
            transform: translateY(-5px);
        }
        
        .donation-option h3 {
            color: var(--primary-blue);
            margin-bottom: 15px;
        }
        
        .donation-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--dark-blue);
            margin: 15px 0;
        }
        
        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            background: var(--light-gray);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        /* Animation for page load */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        
        .delay-1 {
            animation-delay: 0.2s;
        }
        
        .delay-2 {
            animation-delay: 0.4s;
        }
        
        .delay-3 {
            animation-delay: 0.6s;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            nav ul {
                margin: 20px 0;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            nav ul li {
                margin: 0 10px 10px;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 250px;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .feature-card {
                padding: 30px 20px;
            }
            
            .contact-form {
                padding: 30px 20px;
            }
        }
         /* Contact Form */
        .form-container {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 40px;
            margin-top: 60px;
            border: 1px solid rgba(100, 255, 218, 0.1);
            position: relative;
        }
        
        .form-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--text-accent);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(162, 155, 180, 0.6);
            border: 1px solid rgba(100, 255, 218, 0.2);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(213, 218, 216, 1);
        }
        
        textarea.form-input {
            min-height: 150px;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: var(--primary);
            border: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .submit-btn i {
            margin-left: 8px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(100, 255, 218, 0.3);
        }
         /* Contact Grid */

        
        .card-action {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: color 0.3s ease;
        }
        
        .card-action i {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }
        
        .card-action:hover {
            color: var(--text-primary);
        }
        
        .card-action:hover i {
            transform: translateX(4px);
        }
        .meetings-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 20px;
}

.meetings {
  text-align: center;
  max-width: 500px;
  padding: 30px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.meetings h2 {
  color: #2c3e50;
  margin-bottom: 15px;
  font-size: 1.5rem;
}

.meetings p {
  color: #555;
  line-height: 1.6;
  margin-bottom: 20px;
}

.btn {
  display: inline-block;
  padding: 12px 30px;
  background: #3498db;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  border: 2px solid #3498db;
  transition: all 0.3s ease;
  font-weight: 500;
}

.btn:hover {
  background: transparent;
  color: #3498db;
}
        
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                    <div class="logo">
                        <img src="images/logo.png" alt="ANAW Logo" class="logo-img" style="border-radius: 50%;">
                        <div class="logo-text">ANA<span>W</span></div>
                    </div>
                <nav>
                    <ul>
                        <li><a href="#" class="nav-link active" data-tab="home">Home</a></li>
                        <li><a href="#" class="nav-link" data-tab="about">About</a></li>
                        <li><a href="#" class="nav-link" data-tab="programs">Programs</a></li>
                        <li><a href="#" class="nav-link" data-tab="meetings">Meetings</a></li>
                        <li><a href="#" class="nav-link" data-tab="donate">Donate</a></li>
                        <li><a href="#" class="nav-link" data-tab="contact">Contact</a></li>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="login.php" class="btn btn-outline">Dashboard</a>
                        <a href="logout.php" class="btn btn-primary">Login</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline">Login</a>
                        <a href="login.php?register=true" class="btn btn-primary">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Home Tab Content -->
    <section id="home" class="tab-content active">
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="fade-in">ACT OF NETWORKING AND WELFARE</h1>
                    <p class="fade-in delay-1">Supporting communities in Sudan through networking, welfare programs, and global collaboration</p>
                    <div class="hero-buttons">
                        <button class="btn btn-light fade-in delay-2" data-tab="about">Learn More</button>
                        <a href="login.php" class="btn btn-outline fade-in delay-2" style="color: white; border-color: white;">Member Login</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <div class="section-title fade-in">
                    <h2>Our Platform Features</h2>
                    <p>Connecting members worldwide to support communities in Sudan</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card fade-in delay-1">
                        <div class="feature-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <h3>Online Meetings</h3>
                        <p>Join virtual meetings with members from around the world to coordinate efforts and share updates.</p>
                    </div>
                    <div class="feature-card fade-in delay-2">
                        <div class="feature-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3>Admin Updates</h3>
                        <p>Receive important announcements and updates directly from ANAW administrators.</p>
                    </div>
                    <div class="feature-card fade-in delay-3">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3>Donation Tracking</h3>
                        <p>Track your contributions and see how your donations are making an impact in Sudanese communities.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Social Media Section -->
        <section class="social-media">
            <div class="container">
                <div class="section-title fade-in">
                    <h2>Stay Connected</h2>
                    <p>Follow us on social media for the latest updates</p>
                </div>
                <div class="social-icons">
                    <a href="#" class="social-icon fade-in delay-1"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon fade-in delay-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon fade-in delay-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon fade-in delay-1"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon fade-in delay-2"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </section>
    </section>

    <!-- About Tab Content -->
    <section id="about" class="tab-content about-content">
        <div class="container">
            <div class="section-title">
                <h2>About ANAW</h2>
                <p>Learn about our mission, vision, and the team behind our organization</p>
            </div>
            <div class="content-grid">
                <div class="content-card fade-in">
                    <h3>Our Mission</h3>
                    <p>ANAW is dedicated to supporting communities in Sudan through strategic networking and welfare programs. We connect global resources with local needs to create sustainable change.</p>
                    <p>Our approach combines immediate relief with long-term development initiatives, ensuring that communities not only survive but thrive.</p>
                </div>
                <div class="content-card fade-in delay-1">
                    <h3>Our Vision</h3>
                    <p>We envision a Sudan where every community has access to basic necessities, education, healthcare, and economic opportunities.</p>
                    <p>Through collaboration and innovation, we aim to build resilient communities that can shape their own futures.</p>
                </div>
                <div class="content-card fade-in delay-2">
                    <h3>Our Values</h3>
                    <ul>
                        <li><strong>Integrity:</strong> Transparency in all our operations</li>
                        <li><strong>Collaboration:</strong> Working together for greater impact</li>
                        <li><strong>Empowerment:</strong> Building capacity within communities</li>
                        <li><strong>Innovation:</strong> Finding creative solutions to complex problems</li>
                        <li><strong>Sustainability:</strong> Creating lasting change</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Tab Content -->
    <section id="programs" class="tab-content programs-content">
        <div class="container">
            <div class="section-title">
                <h2>Our Programs</h2>
                <p>Discover the initiatives we're implementing to support Sudanese communities</p>
            </div>
            <div class="content-grid">
                <div class="content-card fade-in">
                    <h3>Education Initiative</h3>
                    <p>Providing access to quality education for children in underserved communities through school construction, teacher training, and scholarship programs.</p>
                    <p><strong>Impact:</strong> 5,000+ children enrolled in schools we support</p>
                </div>
                <div class="content-card fade-in delay-1">
                    <h3>Healthcare Access</h3>
                    <p>Improving healthcare delivery through mobile clinics, medical supplies, and health education programs in remote areas.</p>
                    <p><strong>Impact:</strong> 15,000+ patients treated annually</p>
                </div>
                <div class="content-card fade-in delay-2">
                    <h3>Economic Empowerment</h3>
                    <p>Supporting small business development, vocational training, and microfinance programs to create sustainable livelihoods.</p>
                    <p><strong>Impact:</strong> 2,000+ entrepreneurs supported</p>
                </div>
                <div class="content-card fade-in delay-3">
                    <h3>Water & Sanitation</h3>
                    <p>Installing clean water sources and sanitation facilities to prevent waterborne diseases and improve community health.</p>
                    <p><strong>Impact:</strong> 50+ wells constructed serving 30,000 people</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Meetings Tab Content -->
    <section id="meetings" class="tab-content meetings-content">
        <div class="container">
            <div class="section-title">
                <h2>Upcoming Meetings</h2>
                <p>Join our meetings via the links exclusively available at your Dashboard where we discuss progress, challenges, and future plans. All our members are encouraged to attend</p>
            </div>
           <div class="meetings-container">
  <div class="meetings">
    <h2>New members and Our donor meetings (Virtual)</h2>
    <p>In case of new members, investors and donors, remember to get in touch with our Admins or the Tech Lead</p>
    <a href="index.php" class="btn btn-outline">Register</a>
  </div>
</div>
        </div>
    </section>

    <!-- Donate Tab Content -->
    <section id="donate" class="tab-content donate-content">
        <div class="container">
            <div class="section-title">
                <h2>Support Our Mission</h2>
                <p>Your donation directly impacts communities in Sudan</p>
            </div>
            <div class="content-card fade-in">
                <h3>Where Your Money Goes</h3>
                <p>90% of all donations directly fund our programs in Sudan. The remaining 10% covers essential operational costs.</p>
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; color: var(--primary-blue);">90%</div>
                        <div>Programs</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; color: var(--primary-blue);">7%</div>
                        <div>Administration</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; color: var(--primary-blue);">3%</div>
                        <div>Fundraising</div>
                    </div>
                </div>
            </div>
            
            <div class="donation-options fade-in delay-1">
                <div class="donation-option">
                    <h3>Basic Needs</h3>
                    <p>Provide food and essential supplies for a family for one month</p>
                    <div class="donation-amount">$50</div>
                    <button class="btn btn-primary">Donate Now</button>
                </div>
                <div class="donation-option">
                    <h3>Education</h3>
                    <p>Sponsor a child's education for an entire year</p>
                    <div class="donation-amount">$150</div>
                    <button class="btn btn-primary">Donate Now</button>
                </div>
                <div class="donation-option">
                    <h3>Healthcare</h3>
                    <p>Cover medical expenses for 10 patients</p>
                    <div class="donation-amount">$100</div>
                    <button class="btn btn-primary">Donate Now</button>
                </div>
                <div class="donation-option">
                    <h3>Custom Amount</h3>
                    <p>Choose your own donation amount</p>
                    <div style="margin: 20px 0;">
                        <input type="number" class="form-control" placeholder="Enter amount" style="text-align: center;">
                    </div>
                    <button class="btn btn-primary">Donate Now</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Tab Content -->
    <section id="contact" class="tab-content contact-content">
        <div class="container">
            <div class="section-title">
                <h2>Contact Us</h2>
                <p>Get in touch with our team</p>
            </div>
            <div class="content-grid">
                <div class="content-card fade-in">
                    <h3>Our Offices</h3>
                    <p><strong>Headquarters:</strong><br>
                    123 Charity Avenue<br>
                    Kauda, Sudan</p>
                    
                    <p><strong>Tech lead:</strong><br>
                    254<br>
                    NAIROBI, NRB 01200, KENYA</p>
                    
                    <h4 style="margin-top: 20px;">Contact Information</h4>
                    <p><i class="fas fa-envelope"></i> hydratech@gmail.com</p>
                    <p><i class="fas fa-phone"></i> +2547 9640 0227-</p>
                    <p><i class="fas fa-clock"></i> Mon-Fri: 9AM-5PM EST</p>
                </div>
                <div class="content-card fade-in delay-1">
                    <h3>Send Us a Message</h3>
                        <form
id="contactForm"
      action="https://formsubmit.co/hydratech438@gmail.com"
      method="POST"
      novalidate
      aria-describedby="formInstructions"
    >
      <input type="hidden" name="_subject" value="New Strategic Collaboration Proposal" />
      <input type="hidden" name="_template" value="table" />
      <input type="hidden" name="_next" value="https://yourdomain.com/thank-you.html" />
      <!-- CSRF token placeholder for server-side implementation -->
      <input type="hidden" name="_csrf" value="<!-- CSRF_TOKEN_HERE -->" />

      <p id="formInstructions" class="sr-only">
        Please fill out the form below to submit your strategic collaboration proposal.
      </p>

      <div class="form-grid">
        <div class="form-group">
          <input
            type="text"
            id="name"
            name="name"
            class="form-input"
            placeholder=" "
            required
            aria-required="true"
            aria-describedby="nameError"
            autocomplete="name"
          />
          <label for="name">Full Name</label>
          <div id="nameError" class="error-message" aria-live="polite"></div>
        </div>

        <div class="form-group">
          <input
            type="email"
            id="email"
            name="email"
            class="form-input"
            placeholder=" "
            required
            aria-required="true"
            aria-describedby="emailError"
            autocomplete="email"
          />
          <label for="email">Email Address</label>
          <div id="emailError" class="error-message" aria-live="polite"></div>
        </div>

        <div class="form-group">
          <input
            type="text"
            id="company"
            name="company"
            class="form-input"
            placeholder=" "
            aria-describedby="companyError"
            autocomplete="organization"
          />
          <label for="company">Company / Organization</label>
          <div id="companyError" class="error-message" aria-live="polite"></div>
        </div>

        <div class="form-group">
          <input
            type="text"
            id="project"
            name="project"
            class="form-input"
            placeholder=" "
            aria-describedby="projectError"
          />
          <label for="project">Project Type</label>
          <div id="projectError" class="error-message" aria-live="polite"></div>
        </div>

        <div class="form-group full-width">
          <textarea
            id="message"
            name="message"
            class="form-input"
            placeholder=" "
            required
            aria-required="true"
            aria-describedby="messageError"
            rows="5"
          ></textarea>
          <label for="message">Project Vision</label>
          <div id="messageError" class="error-message" aria-live="polite"></div>
        </div>

        <div class="form-group full-width">
          <button type="submit" class="submit-btn" aria-label="Transmit Proposal">
            Submit Email <i class="fas fa-paper-plane" aria-hidden="true"></i>
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Contact Cards -->

    <div class="contact-card" tabindex="0" role="region" aria-labelledby="messagingTitle">
      <h3 id="messagingTitle" class="card-title">Whatsapp</h3>
      <p class="card-content">
        Quick conversations for urgent matters and brief updates.
      </p>
      <a href="https://wa.me/254796400227" class="card-action" target="_blank" rel="noopener noreferrer" aria-label="Start WhatsApp chat with +254796400227">
        Start Chat <i class="fas fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>

                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column fade-in">
                    <h3>About ANAW</h3>
                    <ul>
                        <li><a href="#" data-tab="about">Our Mission</a></li>
                        <li><a href="#" data-tab="about">Our Team</a></li>
                        <li><a href="#" data-tab="about">Impact Stories</a></li>
                        <li><a href="#" data-tab="about">Financials</a></li>
                    </ul>
                </div>
                <div class="footer-column fade-in delay-1">
                    <h3>Get Involved</h3>
                    <ul>
                        <li><a href="#" data-tab="programs">Volunteer</a></li>
                        <li><a href="#" data-tab="donate">Donate</a></li>
                        <li><a href="#" data-tab="about">Partner With Us</a></li>
                        <li><a href="#" data-tab="meetings">Events</a></li>
                    </ul>
                </div>
                <div class="footer-column fade-in delay-2">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="#">News & Updates</a></li>
                        <li><a href="#">Annual Reports</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#" data-tab="contact">FAQs</a></li>
                    </ul>
                </div>
                <div class="footer-column fade-in delay-3">
                    <h3>Contact Us</h3>
                    <ul>
                        <li><a href="#" data-tab="contact">info@anaw.org</a></li>
                        <li><a href="#" data-tab="contact">+254 79640 0227</a></li>
                        <li><a href="#" data-tab="contact">Global Offices</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 ACT OF NETWORKING AND WELFARE (ANAW). All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Tab navigation functionality
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const tabContents = document.querySelectorAll('.tab-content');
            
            // Function to switch tabs
            function switchTab(tabId) {
                // Hide all tab contents
                tabContents.forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Show selected tab content
                document.getElementById(tabId).classList.add('active');
                
                // Update active nav link
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('data-tab') === tabId) {
                        link.classList.add('active');
                    }
                });
                
                // Scroll to top of page
                window.scrollTo(0, 0);
            }
            
            // Add click event to nav links
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });
            
            // Add click event to footer links with data-tab attribute
            document.querySelectorAll('footer a[data-tab]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });
            
            // Add click event to "Learn More" button
            document.querySelector('.btn-light[data-tab]').addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                switchTab(tabId);
            });
            
            // Add scroll effect to header
            window.addEventListener('scroll', function() {
                const header = document.querySelector('header');
                if (window.scrollY > 50) {
                    header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
                } else {
                    header.style.boxShadow = 'var(--shadow-light)';
                }
            });

            // Add intersection observer for animations
            const fadeElements = document.querySelectorAll('.fade-in');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            fadeElements.forEach(el => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                observer.observe(el);
            });
            
            // Form submission handler
            document.querySelector('.contact-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Thank you for your message! We will get back to you soon.');
                this.reset();
            });
            
            // Donation button handlers
            document.querySelectorAll('.donation-option .btn-primary').forEach(button => {
                button.addEventListener('click', function() {
                    const amount = this.parentElement.querySelector('.donation-amount')?.textContent || 
                                  this.parentElement.querySelector('input')?.value;
                    alert(`Thank you for your donation of ${amount}! You will be redirected to our secure payment portal.`);
                });
            });
            
            // Meeting join button handlers
            document.querySelectorAll('.meeting-list .btn-primary').forEach(button => {
                button.addEventListener('click', function() {
                    alert('You are being redirected to the meeting. Please make sure you have Zoom installed.');
                });
            });
            
            // Meeting register button handlers
            document.querySelectorAll('.meeting-list .btn-outline').forEach(button => {
                button.addEventListener('click', function() {
                    alert('Thank you for registering! You will receive a confirmation email with event details.');
                });
            });
        });
    </script>
</body>
</html>