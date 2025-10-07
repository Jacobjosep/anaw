<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user is a member
if ($_SESSION['user_role'] != 'member') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Handle donation submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_donation'])) {
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];
    $donation_type = $_POST['donation_type'];
    $project_id = $_POST['project_id'];
    
    $sql = "INSERT INTO donations (member_id, amount, currency, donation_type, project_id, donation_date) VALUES (?, ?, ?, ?, ?, CURDATE())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idssi", $user_id, $amount, $currency, $donation_type, $project_id);
    $stmt->execute();
    $donation_message = "Donation recorded successfully!";
}

// Get member data
$member_donations = getMemberDonations($conn, $user_id);
$upcoming_meetings = $conn->query("SELECT * FROM meetings WHERE meeting_date > NOW() AND status = 'scheduled' ORDER BY meeting_date ASC LIMIT 5");
$recent_announcements = $conn->query("SELECT * FROM announcements WHERE target_audience IN ('all', 'members') ORDER BY created_at DESC LIMIT 5");
$active_projects = $conn->query("SELECT * FROM projects WHERE status = 'active'");
$member_donation_history = $conn->query("SELECT d.*, p.title as project_title FROM donations d LEFT JOIN projects p ON d.project_id = p.id WHERE d.member_id = $user_id ORDER BY d.donation_date DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - ANAW</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Add all CSS from admin.php but adjust colors for member dashboard */
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .member-badge {
            background-color: var(--primary-blue);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            color: var(--white);
        }
        
        .btn-success {
            background-color: #28a745;
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-blue);
        }
        
        .btn-outline:hover {
            background-color: var(--light-blue);
        }
        
        /* Dashboard Styles */
        .dashboard {
            padding: 40px 0;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: var(--white);
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .stats-card {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .dashboard-nav {
            display: flex;
            background-color: var(--white);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex-wrap: wrap;
        }
        
        .dashboard-nav a {
            margin-right: 20px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
        }
        
        .dashboard-nav a.active {
            background-color: var(--light-blue);
            color: var(--primary-blue);
        }
        
        .dashboard-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        .member-section {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .update-item, .meeting-item, .donation-item, .project-item {
            background-color: var(--light-gray);
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .update-header, .meeting-header, .donation-header, .project-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .update-title, .meeting-title, .donation-title, .project-title {
            font-weight: 600;
        }
        
        .update-date, .meeting-date, .donation-date, .project-info {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .update-content, .meeting-info, .donation-amount {
            color: var(--text-light);
        }
        
        .donation-amount {
            font-weight: 600;
            color: var(--primary-blue);
        }
        
        .social-icons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.3);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .social-icon:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-blue);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        /* Footer */
        footer {
            background-color: var(--dark-blue);
            color: var(--white);
            padding: 30px 0 20px;
            margin-top: 50px;
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: var(--light-blue);
        }
        
        @media (max-width: 768px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="images/logo.png" alt="ANAW Logo" class="logo-img">
                    <div class="logo-text">ANA<span>W</span></div>
                </div>
                <div class="user-info">
                    <span>Welcome, <?php echo $user_name; ?></span>
                    <span class="member-badge">MEMBER</span>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Dashboard -->
    <div class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Member Dashboard</h1>
                <p>Welcome to your ANAW member portal</p>
                <div class="stats-card">
                    <div class="stat-number">$<?php echo number_format($member_donations, 2); ?></div>
                    <div class="stat-label">Your Total Donations</div>
                </div>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="dashboard-nav">
                <a href="#" class="active">Dashboard</a>
                <a href="#" onclick="openModal('donationModal')">Make Donation</a>
                <a href="#">My Donations</a>
                <a href="#">Meetings</a>
                <a href="#">Profile</a>
            </div>
            
            <div class="dashboard-content">
                <div>
                    <div class="member-section">
                        <h3 class="section-title">Recent Announcements</h3>
                        <?php while($announcement = $recent_announcements->fetch_assoc()): ?>
                        <div class="update-item">
                            <div class="update-header">
                                <div class="update-title"><?php echo htmlspecialchars($announcement['title']); ?></div>
                                <div class="update-date"><?php echo date('M j, Y', strtotime($announcement['created_at'])); ?></div>
                            </div>
                            <div class="update-content">
                                <?php echo htmlspecialchars($announcement['content']); ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="member-section">
                        <h3 class="section-title">Active Projects</h3>
                        <?php while($project = $active_projects->fetch_assoc()): ?>
                        <div class="project-item">
                            <div class="project-header">
                                <div class="project-title"><?php echo htmlspecialchars($project['title']); ?></div>
                                <div class="project-info">Budget: $<?php echo number_format($project['budget'], 2); ?></div>
                            </div>
                            <div class="update-content">
                                <?php echo htmlspecialchars($project['description']); ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <div>
                    <div class="member-section">
                        <h3 class="section-title">Upcoming Meetings</h3>
                        <?php while($meeting = $upcoming_meetings->fetch_assoc()): ?>
                        <div class="meeting-item">
                            <div class="meeting-header">
                                <div class="meeting-title"><?php echo htmlspecialchars($meeting['title']); ?></div>
                                <div class="meeting-date"><?php echo date('M j', strtotime($meeting['meeting_date'])); ?></div>
                            </div>
                            <div class="meeting-info">
                                <?php echo date('g:i A', strtotime($meeting['meeting_date'])); ?> • 
                                <a href="<?php echo $meeting['meeting_link']; ?>" target="_blank" class="btn btn-primary btn-small" style="padding: 2px 8px; font-size: 0.8rem;">Join</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="member-section">
                        <h3 class="section-title">My Recent Donations</h3>
                        <?php while($donation = $member_donation_history->fetch_assoc()): ?>
                        <div class="donation-item">
                            <div class="donation-header">
                                <div class="donation-title"><?php echo $donation['project_title'] ?: 'General Donation'; ?></div>
                                <div class="donation-date"><?php echo date('M j, Y', strtotime($donation['donation_date'])); ?></div>
                            </div>
                            <div class="donation-amount">$<?php echo number_format($donation['amount'], 2); ?></div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donation Modal -->
    <div class="modal" id="donationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Make a Donation</h2>
                <button class="close-modal" onclick="closeModal('donationModal')">&times;</button>
            </div>
            <?php if(isset($donation_message)): ?>
                <div class="success-message"><?php echo $donation_message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="amount">Amount ($)</label>
                    <input type="number" id="amount" name="amount" class="form-control" step="0.01" min="1" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="currency">Currency</label>
                        <select id="currency" name="currency" class="form-control" required>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="donation_type">Donation Type</label>
                        <select id="donation_type" name="donation_type" class="form-control" required>
                            <option value="one-time">One-time</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="annual">Annual</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="project_id">Project (Optional)</label>
                    <select id="project_id" name="project_id" class="form-control">
                        <option value="">General Fund</option>
                        <?php 
                        $projects = $conn->query("SELECT * FROM projects WHERE status IN ('planning', 'active')");
                        while($project = $projects->fetch_assoc()): 
                        ?>
                        <option value="<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['title']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="submit_donation" class="btn btn-success">Submit Donation</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; 2023 ACT OF NETWORKING AND WELFARE (ANAW). All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>