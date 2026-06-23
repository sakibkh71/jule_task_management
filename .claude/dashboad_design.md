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
┌─────────────────────────────────────────────┐
│  ☰  [Collapse/Expand]  Search Bar  🔔  👤  │
└─────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│  ☰  LOGO                            [👤]                       │
│                                                                ▼    │
│                              ┌─────────────────────────────────┐    │
│                              │  John Doe                      │    │
│                              │  john@email.com                │    │
│                              ├─────────────────────────────────┤    │
│                              │  👤  My Profile                │    │ link with profile page
│                              │  🔑  Reset Password            │    │ link with reset password page
│                              ├─────────────────────────────────┤    │
│                              │  🚪  Logout                    │    │ functional logout
│                              └─────────────────────────────────┘    │
└──────────────────────────────────────────────────────────────────────┘
## 🎯 Objective
Redesign the existing dashboard to have:
- Left sidebar menu with collapse/expand functionality
- Top navbar with user profile and notifications
- Orange & white color scheme
- Full responsive using Bootstrap 5
- Bootstrap icons for menu items
- Counters ex: tasks , users, done must be dynamic(get data from db)
- Recent Tasks table data also need to dynamic
- design a functional page to reset password (current pass, new password , confirm new password) 
- set dashboard layout to other pages. after login full website have same layout with same color
---