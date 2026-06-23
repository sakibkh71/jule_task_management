# Dashboard Redesign - Task Management System
┌─────────────────────────────────────────────────────────────┐
│  ☰ LOGO                    Dashboard    Profile  🔔  👤   │  ← Top Navbar
├──────┬────────────────────────────────────────────────────┤
│      │                                                     │
│ 🏠   │  📊 Dashboard Overview                             │  ← Main Content
│ 📋   │                                                    │
│ 👥   │  ┌──────────┐  ┌──────────┐  ┌──────────┐        │
│ ⚙️   │  │ Tasks: 45 │  │ Users: 12│  │ Done: 30 │        │  ← Stats Cards
│ 📊   │  └──────────┘  └──────────┘  └──────────┘        │
│ 🚪   │                                                    │
│      │  ┌────────────────────────────────────────────┐    │
│      │  │  Recent Tasks                              │    │  ← Task Table
│      │  ├────────────────────────────────────────────┤    │
│      │  │ Task 1    Status    Due    Action          │    │
│      │  │ Task 2    Status    Due    Action          │    │
│      │  └────────────────────────────────────────────┘    │
│      │                                                    │
├──────┴────────────────────────────────────────────────────┤
│  © 2026 Task Manager                                      │  ← Footer
└─────────────────────────────────────────────────────────────┘
┌─────────────────────┐
│  📝 Task Manager    │  ← Logo/Brand (orange)
│                     │
│  🏠  Dashboard      │  ← Active (orange background)
│  📋  All Tasks      │
│  ➕  Create Task    │
│  👥  Users          │  ← Admin only
│  ⚙️  Settings       │
│                     │
│  ───────────────    │
│  🚪  Logout        │
└─────────────────────┘

┌─────────────────────────────────┐
│  ┌───────────────────────────┐  │
│  │  John Doe                 │  │  ← Full Name (bold)
│  │  john.doe@email.com       │  │  ← Email (small, gray)
│  ├───────────────────────────┤  │
│  │  👤  My Profile           │  │  ← Click → goes to /profile
│  │  🔑  Reset Password       │  │  ← Click → goes to /password/reset
│  ├───────────────────────────┤  │
│  │  🚪  Logout               │  │  ← Click → logs out
│  └───────────────────────────┘  │
└─────────────────────────────────┘
┌─────────────────────────────────────────────┐
│  ☰  [Collapse/Expand]  Search Bar  🔔  👤  │
└─────────────────────────────────────────────┘
## 🎯 Objective
Redesign the existing dashboard to have:
- Left sidebar menu with collapse/expand functionality
- Top navbar with user profile and notifications
- Orange & white color scheme
- Full responsive using Bootstrap 5
- Bootstrap icons for menu items
- Counters ex: tasks , users, done must be dynamic(get data from db)
- Recent Tasks table data also need to dynamic
- Top nav bar user icon in right need to dropdown on click/hover child menu spand (logged in username(link with logged in user profile) , Reset password (with page link > warkable page to reset password, Logout ))
---

## 📁 Project Structure to Modify
