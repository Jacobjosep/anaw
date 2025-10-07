<?php
session_start();
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_member'])) {
        // Add new member
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'];
        $country = $_POST['country'];
        
        $sql = "INSERT INTO users (name, email, password, phone, country, role) VALUES (?, ?, ?, ?, ?, 'member')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $password, $phone, $country);
        $stmt->execute();
        $member_message = "Member added successfully!";
        
    } elseif (isset($_POST['add_meeting'])) {
        // Add new meeting
        $title = $_POST['title'];
        $description = $_POST['description'];
        $meeting_link = $_POST['meeting_link'];
        $meeting_date = $_POST['meeting_date'];
        $duration = $_POST['duration'];
        
        $sql = "INSERT INTO meetings (title, description, meeting_link, meeting_date, duration, created_by) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $title, $description, $meeting_link, $meeting_date, $duration, $user_id);
        $stmt->execute();
        $meeting_message = "Meeting scheduled successfully!";
        
    } elseif (isset($_POST['add_project'])) {
        // Add new project
        $title = $_POST['title'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $budget = $_POST['budget'];
        $status = $_POST['status'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        
        $sql = "INSERT INTO projects (title, description, location, budget, status, start_date, end_date, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdsssi", $title, $description, $location, $budget, $status, $start_date, $end_date, $user_id);
        $stmt->execute();
        $project_message = "Project added successfully!";
        
    } elseif (isset($_POST['add_announcement'])) {
        // Add new announcement
        $title = $_POST['announcement_title'];
        $content = $_POST['content'];
        $type = $_POST['type'];
        $priority = $_POST['priority'];
        $target_audience = $_POST['target_audience'];
        
        $sql = "INSERT INTO announcements (title, content, type, priority, created_by, target_audience) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssis", $title, $content, $type, $priority, $user_id, $target_audience);
        $stmt->execute();
        $announcement_message = "Announcement published successfully!";
        
    } elseif (isset($_POST['delete_member'])) {
        // Delete member
        $member_id = $_POST['member_id'];
        $sql = "DELETE FROM users WHERE id = ? AND role = 'member'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $member_message = "Member deleted successfully!";
    }
}

// Get statistics
$total_members = getTotalMembers($conn);
$total_donations = getTotalDonations($conn);
$active_projects = getActiveProjects($conn);
$upcoming_meetings = $conn->query("SELECT COUNT(*) as count FROM meetings WHERE meeting_date > NOW() AND status = 'scheduled'")->fetch_assoc()['count'];

// Get data for tables
$members = $conn->query("SELECT * FROM users WHERE role = 'member' ORDER BY created_at DESC LIMIT 5");
$recent_meetings = $conn->query("SELECT m.*, u.name as created_by_name FROM meetings m LEFT JOIN users u ON m.created_by = u.id ORDER BY m.meeting_date DESC LIMIT 5");
$recent_projects = $conn->query("SELECT p.*, u.name as created_by_name FROM projects p LEFT JOIN users u ON p.created_by = u.id ORDER BY p.created_at DESC LIMIT 5");
$recent_announcements = $conn->query("SELECT a.*, u.name as created_by_name FROM announcements a LEFT JOIN users u ON a.created_by = u.id ORDER BY a.created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ANAW</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Add all previous CSS styles here */
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
            max-width: 1400px;
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
        
        .admin-badge {
            background-color: var(--dark-blue);
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
        
        .btn-danger {
            background-color: #dc3545;
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
            background-color: var(--dark-blue);
            color: var(--white);
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: var(--text-light);
        }
        
        .dashboard-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .admin-section {
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
        
        .user-item, .meeting-item, .project-item, .announcement-item {
            background-color: var(--light-gray);
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .user-info-small, .meeting-info, .project-info, .announcement-info {
            flex: 1;
        }
        
        .user-name, .meeting-title, .project-title, .announcement-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .user-email, .meeting-date, .project-dates, .announcement-date {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 0.8rem;
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
            background-color: var(--light-blue);
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .social-icon:hover {
            background-color: var(--primary-blue);
            color: var(--white);
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
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: var(--white);
            border-radius: 8px;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
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
                    <img src="logo.png" alt="ANAW Logo" class="logo-img">
                    <div class="logo-text">ANA<span>W</span></div>
                </div>
                <div class="user-info">
                    <span>Welcome, <?php echo $user_name; ?></span>
                    <span class="admin-badge">ADMIN</span>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Dashboard -->
    <div class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Admin Dashboard</h1>
                <p>Manage ANAW members, meetings, projects, and announcements</p>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="dashboard-nav">
                <a href="#" class="active">Dashboard</a>
                <a href="#" onclick="openModal('membersModal')">Manage Members</a>
                <a href="#" onclick="openModal('meetingsModal')">Schedule Meeting</a>
                <a href="#" onclick="openModal('projectsModal')">Add Project</a>
                <a href="#" onclick="openModal('announcementsModal')">Post Announcement</a>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_members; ?></div>
                    <div class="stat-label">Total Members</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">$<?php echo number_format($total_donations, 2); ?></div>
                    <div class="stat-label">Total Donations</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $active_projects; ?></div>
                    <div class="stat-label">Active Projects</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $upcoming_meetings; ?></div>
                    <div class="stat-label">Upcoming Meetings</div>
                </div>
            </div>
            
            <div class="dashboard-content">
                <div>
                    <div class="admin-section">
                        <div class="section-title">
                            <h3>Recent Members</h3>
                            <button class="btn btn-primary btn-small" onclick="openModal('membersModal')">Add Member</button>
                        </div>
                        <?php while($member = $members->fetch_assoc()): ?>
                        <div class="user-item">
                            <div class="user-info-small">
                                <div class="user-name"><?php echo htmlspecialchars($member['name']); ?></div>
                                <div class="user-email"><?php echo htmlspecialchars($member['email']); ?></div>
                            </div>
                            <div class="action-buttons">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                    <button type="submit" name="delete_member" class="btn btn-danger btn-small" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="admin-section">
                        <div class="section-title">
                            <h3>Recent Projects</h3>
                            <button class="btn btn-primary btn-small" onclick="openModal('projectsModal')">Add Project</button>
                        </div>
                        <?php while($project = $recent_projects->fetch_assoc()): ?>
                        <div class="project-item">
                            <div class="project-info">
                                <div class="project-title"><?php echo htmlspecialchars($project['title']); ?></div>
                                <div class="project-dates">
                                    Budget: $<?php echo number_format($project['budget'], 2); ?> | 
                                    Status: <?php echo ucfirst($project['status']); ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <div>
                    <div class="admin-section">
                        <div class="section-title">
                            <h3>Upcoming Meetings</h3>
                            <button class="btn btn-primary btn-small" onclick="openModal('meetingsModal')">Schedule Meeting</button>
                        </div>
                        <?php while($meeting = $recent_meetings->fetch_assoc()): ?>
                        <div class="meeting-item">
                            <div class="meeting-info">
                                <div class="meeting-title"><?php echo htmlspecialchars($meeting['title']); ?></div>
                                <div class="meeting-date">
                                    <?php echo date('M j, Y g:i A', strtotime($meeting['meeting_date'])); ?> | 
                                    <a href="<?php echo $meeting['meeting_link']; ?>" target="_blank">Join Meeting</a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="admin-section">
                        <div class="section-title">
                            <h3>Recent Announcements</h3>
                            <button class="btn btn-primary btn-small" onclick="openModal('announcementsModal')">Post Announcement</button>
                        </div>
                        <?php while($announcement = $recent_announcements->fetch_assoc()): ?>
                        <div class="announcement-item">
                            <div class="announcement-info">
                                <div class="announcement-title"><?php echo htmlspecialchars($announcement['title']); ?></div>
                                <div class="announcement-date">
                                    <?php echo date('M j, Y', strtotime($announcement['created_at'])); ?> | 
                                    Type: <?php echo ucfirst($announcement['type']); ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Add Member Modal -->
    <div class="modal" id="membersModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Member</h2>
                <button class="close-modal" onclick="closeModal('membersModal')">&times;</button>
            </div>
            <?php if(isset($member_message)): ?>
                <div class="success-message"><?php echo $member_message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" class="form-control">
                    </div>
                </div>
                <button type="submit" name="add_member" class="btn btn-primary">Add Member</button>
            </form>
        </div>
    </div>

    <!-- Schedule Meeting Modal -->
    <div class="modal" id="meetingsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Schedule Meeting</h2>
                <button class="close-modal" onclick="closeModal('meetingsModal')">&times;</button>
            </div>
            <?php if(isset($meeting_message)): ?>
                <div class="success-message"><?php echo $meeting_message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Meeting Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="meeting_link">Google Meet Link</label>
                    <input type="url" id="meeting_link" name="meeting_link" class="form-control" placeholder="https://meet.google.com/abc-def-ghi" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="meeting_date">Meeting Date & Time</label>
                        <input type="datetime-local" id="meeting_date" name="meeting_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="duration">Duration (minutes)</label>
                        <input type="number" id="duration" name="duration" class="form-control" value="60" required>
                    </div>
                </div>
                <button type="submit" name="add_meeting" class="btn btn-primary">Schedule Meeting</button>
            </form>
        </div>
    </div>

    <!-- Add Project Modal -->
    <div class="modal" id="projectsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Project</h2>
                <button class="close-modal" onclick="closeModal('projectsModal')">&times;</button>
            </div>
            <?php if(isset($project_message)): ?>
                <div class="success-message"><?php echo $project_message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Project Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" class="form-control">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="budget">Budget ($)</label>
                        <input type="number" id="budget" name="budget" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="planning">Planning</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control">
                    </div>
                </div>
                <button type="submit" name="add_project" class="btn btn-primary">Add Project</button>
            </form>
        </div>
    </div>

    <!-- Post Announcement Modal -->
    <div class="modal" id="announcementsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Post Announcement</h2>
                <button class="close-modal" onclick="closeModal('announcementsModal')">&times;</button>
            </div>
            <?php if(isset($announcement_message)): ?>
                <div class="success-message"><?php echo $announcement_message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="announcement_title">Title</label>
                    <input type="text" id="announcement_title" name="announcement_title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" class="form-control" rows="5" required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="general">General</option>
                            <option value="project">Project Update</option>
                            <option value="meeting">Meeting</option>
                            <option value="financial">Financial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" class="form-control" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="target_audience">Target Audience</label>
                    <select id="target_audience" name="target_audience" class="form-control" required>
                        <option value="all">All Users</option>
                        <option value="members">Members Only</option>
                        <option value="admins">Admins Only</option>
                    </select>
                </div>
                <button type="submit" name="add_announcement" class="btn btn-primary">Post Announcement</button>
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
        
        // Set minimum date for meeting and project dates
        document.getElementById('meeting_date').min = new Date().toISOString().slice(0, 16);
        document.getElementById('start_date').min = new Date().toISOString().slice(0, 10);
    </script>
</body>
</html>