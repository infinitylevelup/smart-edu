@extends('layouts.app')

@section('title', 'پنل دانش‌آموز - داشبورد')

@push('styles')
    <style>
        /* تم SmartEdu */
        :root {
            --primary: #7B68EE;
            --primary-light: rgba(123, 104, 238, 0.1);
            --primary-gradient: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --secondary: #FF6B9D;
            --secondary-light: rgba(255, 107, 157, 0.1);
            --accent: #00D4AA;
            --accent-light: rgba(0, 212, 170, 0.1);
            --gold: #FFD166;
            --light: #ffffff;
            --dark: #2D3047;
            --gray: #8A8D9B;
            --light-gray: #F8F9FF;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
            --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.2);
            --gradient-1: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --gradient-2: linear-gradient(135deg, #00D4AA, #4361EE);
            --gradient-3: linear-gradient(135deg, #FFD166, #FF9A3D);
            --gradient-4: linear-gradient(135deg, #7209B7, #3A0CA3);
            --radius-xl: 24px;
            --radius-lg: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;
        }

        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        body {
            background-color: #f5f7ff;
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .stu-dashboard {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 15px 80px;
            animation: fadeIn 0.6s ease both;
        }

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

        @keyframes slideInLeft {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        /* ========== TOP ACTIONS ========== */
        .top-header {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 25px 30px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 30px;
            border: 2px solid rgba(123, 104, 238, 0.08);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .top-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .top-header h1 {
            font-weight: 900;
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .top-header h1::before {
            content: '';
            width: 8px;
            height: 40px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        .top-subtitle {
            color: var(--gray);
            font-size: 1.05rem;
            line-height: 1.8;
            max-width: 600px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 25px;
            animation: slideInLeft 0.6s ease-out;
        }

        .btn-action {
            padding: 15px 28px;
            border-radius: var(--radius-lg);
            font-weight: 800;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .btn-action:active {
            transform: scale(0.97);
        }

        .btn-primary-grad {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
        }

        .btn-primary-grad:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(123, 104, 238, 0.4);
        }

        .btn-primary-grad::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }

        .btn-primary-grad:hover::before {
            left: 100%;
        }

        .btn-outline-primary-grad {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline-primary-grad:hover {
            background: var(--primary-light);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-secondary-grad {
            background: transparent;
            color: var(--gray);
            border: 2px solid var(--gray);
        }

        .btn-outline-secondary-grad:hover {
            background: var(--light-gray);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        /* ========== WELCOME BANNER ========== */
        .welcome-banner {
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.1), rgba(255, 107, 157, 0.1));
            border-radius: var(--radius-xl);
            padding: 35px 40px;
            margin-bottom: 30px;
            border: 2px solid rgba(123, 104, 238, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
            position: relative;
            overflow: hidden;
            animation: slideInLeft 0.5s ease-out;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(123, 104, 238, 0.08), transparent 70%);
            border-radius: 50%;
        }

        .welcome-content h2 {
            font-weight: 900;
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .welcome-content p {
            color: var(--gray);
            font-size: 1.1rem;
            line-height: 1.9;
            max-width: 700px;
            margin: 0;
        }

        .highlight {
            background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 900;
        }

        .rocket-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--gradient-1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            box-shadow: 0 15px 35px rgba(123, 104, 238, 0.3);
            animation: float 3s ease-in-out infinite, pulse 2s infinite;
            position: relative;
            z-index: 2;
        }

        /* ========== STATS CARDS ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
            animation: fadeIn 0.6s ease-out;
            animation-delay: 0.1s;
            animation-fill-mode: both;
        }

        .stat-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 25px;
            box-shadow: var(--shadow-lg);
            border: 2px solid transparent;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .stat-card:nth-child(2)::before {
            background: linear-gradient(135deg, rgba(0, 212, 170, 0.08), transparent);
        }

        .stat-card:nth-child(3)::before {
            background: linear-gradient(135deg, rgba(255, 209, 102, 0.08), transparent);
        }

        .stat-card:nth-child(4)::before {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.08), transparent);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .stat-title {
            color: var(--gray);
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            line-height: 1;
        }

        .stat-trend {
            font-size: 0.9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
            position: relative;
            z-index: 2;
        }

        .trend-up {
            color: #00D4AA;
        }

        .trend-down {
            color: #FF6B9D;
        }

        /* ========== MAIN DASHBOARD CARDS ========== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 35px;
        }

        .dash-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 30px;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
            animation-fill-mode: both;
        }

        .dash-card:nth-child(1) {
            animation-delay: 0.2s;
        }

        .dash-card:nth-child(2) {
            animation-delay: 0.3s;
        }

        .dash-card:nth-child(3) {
            animation-delay: 0.4s;
        }

        .dash-card:nth-child(4) {
            animation-delay: 0.5s;
        }

        .dash-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .dash-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }

        .card-title {
            font-weight: 900;
            font-size: 1.3rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary);
            background: var(--primary-light);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        /* ========== ADVISOR CARD ========== */
        .advisor-card {
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), rgba(0, 212, 170, 0.05));
        }

        .advisor-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }

        .advisor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 25px rgba(123, 104, 238, 0.3);
            flex-shrink: 0;
            animation: bounce 2s infinite;
        }

        .advisor-details h4 {
            font-weight: 900;
            font-size: 1.2rem;
            color: var(--dark);
            margin: 0 0 5px;
        }

        .advisor-details p {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.7;
            margin: 0;
        }

        .advisor-hours {
            background: var(--primary-light);
            color: var(--primary);
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-block;
            margin-top: 10px;
        }

        .advisor-buttons {
            display: flex;
            gap: 15px;
            position: relative;
            z-index: 2;
        }

        .btn-advisor {
            flex: 1;
            padding: 15px;
            border-radius: var(--radius-lg);
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .btn-advisor-primary {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.25);
        }

        .btn-advisor-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(123, 104, 238, 0.35);
        }

        .btn-advisor-secondary {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
            box-shadow: var(--shadow-sm);
        }

        .btn-advisor-secondary:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* ========== SKILLS PROGRESS ========== */
        .skill-item {
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .skill-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .skill-name {
            font-weight: 800;
            font-size: 1rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .skill-percent {
            font-weight: 900;
            font-size: 1.1rem;
            color: var(--primary);
        }

        .skill-bar {
            height: 12px;
            background: var(--light-gray);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .skill-fill {
            height: 100%;
            border-radius: 10px;
            background: var(--gradient-1);
            width: 0;
            transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .skill-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        /* ========== REPORTS CARD ========== */
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
            position: relative;
            z-index: 2;
        }

        .report-item {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 20px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .report-item:hover {
            border-color: var(--primary-light);
            transform: translateY(-5px);
            background: var(--light);
        }

        .report-value {
            font-size: 2rem;
            font-weight: 900;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
            line-height: 1;
        }

        .report-label {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 700;
        }

        .btn-reports {
            margin-top: 25px;
            width: 100%;
            padding: 18px;
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: var(--radius-lg);
            font-weight: 900;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            text-decoration: none;
            position: relative;
            z-index: 2;
        }

        .btn-reports:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* ========== EXAMS STATUS ========== */
        .exams-list {
            list-style: none;
            margin: 0;
            padding: 0;
            position: relative;
            z-index: 2;
        }

        .exam-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 15px;
            border-radius: var(--radius-md);
            border-bottom: 2px dashed rgba(123, 104, 238, 0.1);
            transition: all 0.3s;
        }

        .exam-item:last-child {
            border-bottom: none;
        }

        .exam-item:hover {
            background: var(--primary-light);
            transform: translateX(5px);
        }

        .exam-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .exam-status {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: block;
            flex-shrink: 0;
        }

        .status-available {
            background: #00D4AA;
            box-shadow: 0 0 0 4px rgba(0, 212, 170, 0.2);
        }

        .status-in-progress {
            background: #FFD166;
            box-shadow: 0 0 0 4px rgba(255, 209, 102, 0.2);
        }

        .status-pending {
            background: #FF6B9D;
            box-shadow: 0 0 0 4px rgba(255, 107, 157, 0.2);
        }

        .exam-name {
            font-weight: 800;
            color: var(--dark);
            font-size: 1rem;
        }

        .exam-date {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 700;
        }

        .btn-start-exam {
            margin-top: 25px;
            width: 100%;
            padding: 18px;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 900;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            text-decoration: none;
            position: relative;
            z-index: 2;
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
        }

        .btn-start-exam:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(123, 104, 238, 0.4);
        }

        /* ========== QUICK ACTIONS ========== */
        .quick-actions-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 35px 40px;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(0, 0, 0, 0.05);
            animation: slideInRight 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .quick-actions-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient-1);
        }

        .quick-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }

        .quick-header h3 {
            font-weight: 900;
            font-size: 1.4rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            position: relative;
            z-index: 2;
        }

        .quick-action {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 22px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid transparent;
            text-align: center;
        }

        .quick-action:hover {
            background: var(--light);
            border-color: var(--primary-light);
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .quick-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            transition: all 0.3s;
        }

        .quick-action:hover .quick-icon {
            background: var(--gradient-1);
            color: white;
            transform: scale(1.1);
        }

        .quick-text {
            font-weight: 900;
            color: var(--dark);
            font-size: 1rem;
            line-height: 1.4;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stu-dashboard {
                padding: 15px 10px 60px;
            }

            .top-header {
                padding: 20px;
            }

            .top-header h1 {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }

            .welcome-banner {
                padding: 25px;
                text-align: center;
                flex-direction: column;
            }

            .welcome-content h2 {
                font-size: 1.6rem;
            }

            .rocket-icon {
                width: 80px;
                height: 80px;
                font-size: 2.2rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-value {
                font-size: 2rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .advisor-info {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .advisor-buttons {
                flex-direction: column;
            }

            .reports-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .report-item {
                padding: 15px;
            }

            .report-value {
                font-size: 1.5rem;
            }

            .quick-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .quick-actions-card {
                padding: 25px 20px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .reports-grid {
                grid-template-columns: 1fr;
            }

            .quick-grid {
                grid-template-columns: 1fr;
            }

            .quick-action {
                padding: 18px 15px;
            }
        }

        /* دکمه‌های لمسی بزرگ */
        .btn-action,
        .btn-advisor,
        .btn-start-exam,
        .btn-reports,
        .quick-action {
            min-height: 48px;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(123, 104, 238, 0.2);
            color: var(--dark);
        }
    </style>
@endpush

@section('content')
    <div class="stu-dashboard">
        {{-- ========== TOP HEADER ========== --}}
        <div class="top-header">
            <div>
                <h1>داشبورد دانش‌آموز</h1>
                <p class="top-subtitle">
                    وضعیت آزمون‌ها، پیشرفت و تحلیل‌های شما در یک نگاه.
                </p>
            </div>

            <div class="action-buttons">
                <a href="{{ route('student.exams.public') }}" class="btn-action btn-primary-grad">
                    <i class="fas fa-globe"></i>
                    آزمون‌های عمومی
                </a>
                <a href="{{ route('student.exams.classroom') }}" class="btn-action btn-outline-primary-grad">
                    <i class="fas fa-people-group"></i>
                    آزمون‌های کلاسی
                </a>
                <a href="{{ route('student.reports.index') }}" class="btn-action btn-outline-secondary-grad">
                    <i class="fas fa-chart-simple"></i>
                    گزارش‌ها و تحلیل‌ها
                </a>
            </div>
        </div>

        {{-- ========== WELCOME BANNER ========== --}}
        <div class="welcome-banner">
            <div class="welcome-content">
                <h2>سلام <span class="highlight">{{ auth()->user()->name ?? 'دانش‌آموز' }}</span>! آماده پیشرفت هستی؟ 🚀
                </h2>
                <p>
                    امروز با یه آزمون کوتاه شروع کن؛ همین قدم‌های کوچیک،
                    تو رو به هدف‌های بزرگ می‌رسونه.
                    <br>
                    تیم پشتیبانی همیشه کنارته تا بهترین نتیجه رو بگیری! ✨
                </p>
            </div>
            <div class="rocket-icon">
                <i class="fas fa-rocket"></i>
            </div>
        </div>

        {{-- ========== STATS ========== --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">آزمون‌های در دسترس</div>
                    <div class="stat-icon" style="background: var(--gradient-1);">
                        <i class="fas fa-file-circle-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['available_exams'] ?? '—' }}</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    نسبت به هفته گذشته
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">آزمون‌های انجام‌شده</div>
                    <div class="stat-icon" style="background: var(--gradient-2);">
                        <i class="fas fa-square-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['taken_exams'] ?? '—' }}</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    {{ $stats['exam_growth'] ?? '12' }}% رشد
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">میانگین نمره</div>
                    <div class="stat-icon" style="background: var(--gradient-3);">
                        <i class="fas fa-gauge-high"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['avg_score'] ?? '—' }}%</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    {{ $stats['score_growth'] ?? '5' }}% بهبود
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">سطح فعلی</div>
                    <div class="stat-icon" style="background: var(--gradient-4);">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['current_level'] ?? '—' }}</div>
                <div class="stat-trend">
                    <span style="color: var(--gold);">
                        <i class="fas fa-star"></i>
                    </span>
                    {{ $stats['next_level'] ?? '2' }} آزمون تا سطح بعدی
                </div>
            </div>
        </div>

        {{-- ========== MAIN DASHBOARD CARDS ========== --}}
        <div class="dashboard-grid">
            {{-- ADVISOR CARD --}}
            <div class="dash-card advisor-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-user-graduate"></i>
                        پشتیبان آموزشی شما
                    </div>
                </div>

                <div class="advisor-info">
                    <div class="advisor-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="advisor-details">
                        <h4>{{ $advisor['name'] ?? 'پشتیبان شما' }}</h4>
                        <p>{{ $advisor['title'] ?? 'مشاور تحصیلی و منتور شخصی' }}</p>
                        <span class="advisor-hours">
                            <i class="fas fa-clock"></i>
                            پاسخگویی: {{ $advisor['hours'] ?? '۸ صبح تا ۸ شب' }}
                        </span>
                    </div>
                </div>

                <div class="advisor-buttons">
                    <a href="{{ $advisorChatUrl ?? route('student.support.index') }}"
                        class="btn-advisor btn-advisor-primary">
                        <i class="fas fa-comment-dots"></i>
                        ارسال پیام
                    </a>
                    <button class="btn-advisor btn-advisor-secondary" type="button"
                        onclick="showComingSoon('تماس تصویری')">
                        <i class="fas fa-video"></i>
                        تماس تصویری
                    </button>
                </div>
            </div>

            {{-- SKILLS CARD --}}
            <div class="dash-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-brain"></i>
                        مهارت‌های درسی
                    </div>
                </div>

                @php
                    $skills = $skills ?? [
                        ['name' => 'ریاضی و حسابان', 'percent' => 85, 'icon' => 'fas fa-calculator'],
                        ['name' => 'فیزیک', 'percent' => 78, 'icon' => 'fas fa-atom'],
                        ['name' => 'شیمی', 'percent' => 70, 'icon' => 'fas fa-flask'],
                        ['name' => 'زیست‌شناسی', 'percent' => 92, 'icon' => 'fas fa-dna'],
                    ];
                @endphp

                @foreach ($skills as $sk)
                    <div class="skill-item">
                        <div class="skill-header">
                            <div class="skill-name">
                                <i class="{{ $sk['icon'] ?? 'fas fa-star' }}"></i>
                                {{ $sk['name'] }}
                            </div>
                            <div class="skill-percent">{{ $sk['percent'] }}%</div>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-fill" data-percent="{{ $sk['percent'] }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- REPORTS CARD --}}
            <div class="dash-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-chart-bar"></i>
                        آخرین گزارش‌ها
                    </div>
                </div>

                <p style="color: var(--gray); margin-bottom: 20px; font-size: 0.95rem; line-height: 1.7;">
                    خلاصه عملکرد اخیر شما در SmartEdu:
                </p>

                <div class="reports-grid">
                    <div class="report-item">
                        <div class="report-value">{{ $stats['avg_score'] ?? '—' }}%</div>
                        <div class="report-label">میانگین نمرات</div>
                    </div>
                    <div class="report-item">
                        <div class="report-value">{{ $stats['taken_exams'] ?? '—' }}</div>
                        <div class="report-label">آزمون انجام‌شده</div>
                    </div>
                    <div class="report-item">
                        <div class="report-value">{{ $stats['study_hours'] ?? '—' }}</div>
                        <div class="report-label">ساعت مطالعه</div>
                    </div>
                    <div class="report-item">
                        <div class="report-value">{{ $stats['badges'] ?? '—' }}</div>
                        <div class="report-label">نشان‌ها</div>
                    </div>
                </div>

                <a href="{{ route('student.reports.index') }}" class="btn-reports">
                    مشاهده گزارش کامل
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            {{-- EXAMS STATUS CARD --}}
            <div class="dash-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-clipboard-list"></i>
                        وضعیت آزمون‌ها
                    </div>
                </div>

                @php
                    $activeExams = $activeExams ?? [
                        ['title' => 'آزمون ریاضی پایه دهم', 'status' => 'available', 'date' => 'امروز'],
                        ['title' => 'فیزیک - فصل دوم', 'status' => 'in_progress', 'date' => 'در حال انجام'],
                        ['title' => 'آزمون جامع شیمی', 'status' => 'pending', 'date' => 'فردا'],
                        ['title' => 'آزمون زبان انگلیسی', 'status' => 'available', 'date' => 'این هفته'],
                    ];
                @endphp

                @if (count($activeExams))
                    <ul class="exams-list">
                        @foreach ($activeExams as $ex)
                            @php
                                $statusClass =
                                    $ex['status'] === 'available'
                                        ? 'status-available'
                                        : ($ex['status'] === 'in_progress'
                                            ? 'status-in-progress'
                                            : 'status-pending');
                            @endphp
                            <li class="exam-item">
                                <div class="exam-info">
                                    <span class="exam-status {{ $statusClass }}"></span>
                                    <span class="exam-name">{{ $ex['title'] }}</span>
                                </div>
                                <span class="exam-date">{{ $ex['date'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4">
                        <div style="font-size: 4rem; color: var(--light-gray); margin-bottom: 15px;">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <p style="color: var(--gray);">فعلاً آزمون فعالی برای نمایش نیست.</p>
                    </div>
                @endif

                <a href="{{ route('student.exams.public') }}" class="btn-start-exam">
                    شروع آزمون جدید
                    <i class="fas fa-play"></i>
                </a>
            </div>
        </div>

        {{-- ========== QUICK ACTIONS ========== --}}
        <div class="quick-actions-card">
            <div class="quick-header">
                <h3>
                    <i class="fas fa-bolt" style="color: var(--primary);"></i>
                    میانبرهای سریع
                </h3>
                <div style="color: var(--gray); font-size: 0.95rem; font-weight: 700;">
                    سریع‌ترین مسیر برای ادامه دادن
                </div>
            </div>

            <div class="quick-grid">
                <a class="quick-action" href="{{ route('student.exams.public') }}">
                    <div class="quick-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="quick-text">آزمون‌های عمومی</div>
                </a>

                <a class="quick-action" href="{{ route('student.exams.classroom') }}">
                    <div class="quick-icon">
                        <i class="fas fa-people-group"></i>
                    </div>
                    <div class="quick-text">آزمون‌های کلاسی</div>
                </a>

                <a class="quick-action" href="{{ route('student.reports.index') }}">
                    <div class="quick-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="quick-text">گزارش‌ها و تحلیل</div>
                </a>

                <a class="quick-action" href="{{ route('student.profile') }}">
                    <div class="quick-icon">
                        <i class="fas fa-user-gear"></i>
                    </div>
                    <div class="quick-text">پروفایل من</div>
                </a>

                <a class="quick-action" href="{{ route('student.support.index') }}">
                    <div class="quick-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="quick-text">پشتیبانی</div>
                </a>

                <a class="quick-action" href="{{ route('student.learning-path') ?? '#' }}">
                    <div class="quick-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="quick-text">برنامه‌ریزی</div>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // انیمیشن مهارت‌ها
            document.querySelectorAll('.skill-fill').forEach(fill => {
                const percent = fill.getAttribute('data-percent') || '0';
                setTimeout(() => {
                    fill.style.width = percent + '%';
                }, 300);
            });

            // انیمیشن کارت‌های آمار
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, i) => {
                card.style.animationDelay = (i * 0.1) + 's';
            });

            // ویبره برای موبایل
            if (navigator.vibrate) {
                const clickableItems = document.querySelectorAll(
                    '.btn-action, .quick-action, .exam-item, .btn-advisor, .btn-start-exam, .btn-reports');
                clickableItems.forEach(item => {
                    item.addEventListener('click', function() {
                        navigator.vibrate(30);
                    });
                });
            }

            // افکت hover برای کارت‌ها
            const dashCards = document.querySelectorAll('.dash-card');
            dashCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(20);
                    }
                });
            });

            // فعال‌سازی کلیک روی کارت آزمون‌ها
            const examItems = document.querySelectorAll('.exam-item');
            examItems.forEach(item => {
                item.addEventListener('click', function() {
                    const examName = this.querySelector('.exam-name').textContent;
                    showExamModal(examName);
                });
            });
        });

        function showComingSoon(feature) {
            const modal = document.createElement('div');
            modal.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        z-index: 1000;
        text-align: center;
        max-width: 350px;
        width: 85%;
        animation: scaleIn 0.3s ease forwards;
        border: 3px solid var(--primary);
    `;

            modal.innerHTML = `
        <div style="font-size: 3rem; margin-bottom: 20px; color: var(--primary);">
            <i class="fas fa-tools"></i>
        </div>
        <h3 style="margin-bottom: 15px; color: var(--dark); font-size: 1.3rem; font-weight: 700;">${feature}</h3>
        <p style="color: var(--gray); margin-bottom: 25px; font-size: 1rem; line-height: 1.6;">
            این قابلیت در حال توسعه است و به زودی در دسترس قرار خواهد گرفت.
        </p>
        <button onclick="this.parentElement.remove(); if (this.parentElement.nextElementSibling) this.parentElement.nextElementSibling.remove();"
                style="padding: 14px 40px; border: none; background: var(--gradient-1); color: white; border-radius: 12px; font-weight: 800; font-size: 1rem; width: 100%;">
            باشه، متوجه شدم
        </button>
    `;

            document.body.appendChild(modal);

            const overlay = document.createElement('div');
            overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        animation: fadeIn 0.3s ease;
    `;
            document.body.appendChild(overlay);

            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }

            setTimeout(() => {
                if (document.body.contains(modal)) {
                    modal.remove();
                    overlay.remove();
                }
            }, 5000);
        }

        function showExamModal(examName) {
            const modal = document.createElement('div');
            modal.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        z-index: 1000;
        text-align: center;
        max-width: 400px;
        width: 90%;
        animation: scaleIn 0.3s ease forwards;
        border: 3px solid var(--primary);
    `;

            modal.innerHTML = `
        <div style="font-size: 3rem; margin-bottom: 20px; color: var(--primary);">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <h3 style="margin-bottom: 15px; color: var(--dark); font-size: 1.3rem; font-weight: 700;">${examName}</h3>
        <p style="color: var(--gray); margin-bottom: 25px; font-size: 1rem; line-height: 1.6;">
            برای شروع این آزمون روی دکمه زیر کلیک کن. زمان آزمون: ۴۵ دقیقه
        </p>
        <div style="display: flex; gap: 10px;">
            <button onclick="this.parentElement.parentElement.remove(); if (this.parentElement.parentElement.nextElementSibling) this.parentElement.parentElement.nextElementSibling.remove();"
                    style="flex:1; padding: 14px; border: none; background: var(--light-gray); color: var(--dark); border-radius: 12px; font-weight: 700; font-size: 1rem;">
                بعداً
            </button>
            <button onclick="window.location.href='{{ route('student.exams.public') }}';"
                    style="flex:1; padding: 14px; border: none; background: var(--gradient-1); color: white; border-radius: 12px; font-weight: 700; font-size: 1rem;">
                شروع آزمون
            </button>
        </div>
    `;

            document.body.appendChild(modal);

            const overlay = document.createElement('div');
            overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        animation: fadeIn 0.3s ease;
    `;
            document.body.appendChild(overlay);

            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }
        }

        // اضافه کردن استایل‌های انیمیشن
        const style = document.createElement('style');
        style.textContent = `
    @keyframes scaleIn {
        from { transform: translate(-50%, -50%) scale(0.9); opacity: 0; }
        to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
`;
        document.head.appendChild(style);
    </script>
@endpush
