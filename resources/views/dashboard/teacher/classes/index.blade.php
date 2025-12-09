@extends('layouts.app')
@section('title', 'پنل معلم - کلاس‌های من')

@push('styles')
    <style>
        /* ========== THEME ENHANCEMENTS ========== */
        :root {
            --primary: #7B68EE;
            --primary-light: rgba(123, 104, 238, 0.1);
            --primary-gradient: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --secondary: #FF6B9D;
            --secondary-light: rgba(255, 107, 157, 0.1);
            --accent: #00D4AA;
            --accent-light: rgba(0, 212, 170, 0.1);
            --warning: #FFD166;
            --warning-light: rgba(255, 209, 102, 0.1);
            --danger: #EF476F;
            --danger-light: rgba(239, 71, 111, 0.1);
            --light: #ffffff;
            --dark: #2D3047;
            --dark-light: #3A3F6D;
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
            --radius-full: 999px;
        }

        * {
            font-family: 'Vazirmatn', 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f2ff 100%);
            min-height: 100vh;
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ========== MAIN CONTAINER ========== */
        .classes-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 25px 20px 100px;
            animation: fadeIn 0.8s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-40px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(40px);
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
                transform: translateY(-15px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(123, 104, 238, 0.4);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(123, 104, 238, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(123, 104, 238, 0);
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

        @keyframes gradientFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* ========== STATS HEADER ========== */
        .stats-header {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 35px;
            animation: slideInRight 0.6s ease-out;
        }

        @media (max-width: 1200px) {
            .stats-header {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-header {
                grid-template-columns: 1fr;
            }
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
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            border-radius: 0 0 0 100%;
            opacity: 0.1;
            transition: all 0.5s;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .stat-card:nth-child(1)::before {
            background: var(--primary);
        }

        .stat-card:nth-child(2)::before {
            background: var(--secondary);
        }

        .stat-card:nth-child(3)::before {
            background: var(--accent);
        }

        .stat-card:nth-child(4)::before {
            background: var(--warning);
        }

        .stat-card:hover::before {
            width: 150px;
            height: 150px;
            opacity: 0.15;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .stat-card:nth-child(1) .stat-icon {
            background: var(--primary-light);
            color: var(--primary);
        }

        .stat-card:nth-child(2) .stat-icon {
            background: var(--secondary-light);
            color: var(--secondary);
        }

        .stat-card:nth-child(3) .stat-icon {
            background: var(--accent-light);
            color: var(--accent);
        }

        .stat-card:nth-child(4) .stat-icon {
            background: var(--warning-light);
            color: #FF9A3D;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            z-index: 2;
        }

        .stat-label {
            color: var(--gray);
            font-weight: 700;
            font-size: 0.95rem;
            position: relative;
            z-index: 2;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-top: 12px;
            position: relative;
            z-index: 2;
        }

        .stat-change.positive {
            color: var(--accent);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* ========== HERO SECTION ENHANCED ========== */
        .hero-section {
            background: linear-gradient(135deg,
                    rgba(123, 104, 238, 0.08) 0%,
                    rgba(255, 107, 157, 0.08) 50%,
                    rgba(0, 212, 170, 0.08) 100%);
            border-radius: var(--radius-xl);
            padding: 40px 45px;
            margin-bottom: 40px;
            border: 2px solid rgba(123, 104, 238, 0.2);
            position: relative;
            overflow: hidden;
            animation: slideInLeft 0.5s ease-out;
            backdrop-filter: blur(10px);
        }

        .hero-section::before,
        .hero-section::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .hero-section::before {
            top: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(123, 104, 238, 0.15), transparent 70%);
            animation-delay: -2s;
        }

        .hero-section::after {
            bottom: -60px;
            left: -60px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(0, 212, 170, 0.15), transparent 70%);
            animation-delay: -4s;
        }

        .hero-content h1 {
            font-weight: 900;
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
            z-index: 2;
        }

        .hero-title-gradient {
            background: linear-gradient(120deg,
                    var(--primary) 0%,
                    var(--secondary) 30%,
                    var(--accent) 70%,
                    var(--primary) 100%);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientFlow 4s ease infinite;
        }

        .hero-subtitle {
            color: var(--gray);
            font-size: 1.15rem;
            line-height: 1.8;
            max-width: 800px;
            margin: 0;
            position: relative;
            z-index: 2;
            font-weight: 500;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            margin-top: 35px;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .btn-hero {
            padding: 18px 36px;
            border-radius: var(--radius-lg);
            font-weight: 800;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 14px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            border: 3px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            justify-content: center;
        }

        .btn-hero:active {
            transform: scale(0.96);
        }

        .btn-primary-grad {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 12px 30px rgba(123, 104, 238, 0.35);
        }

        .btn-primary-grad:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 20px 40px rgba(123, 104, 238, 0.5);
        }

        .btn-primary-grad::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.7s;
        }

        .btn-primary-grad:hover::before {
            left: 100%;
        }

        .btn-success-grad {
            background: var(--gradient-2);
            color: white;
            box-shadow: 0 12px 30px rgba(0, 212, 170, 0.35);
        }

        .btn-success-grad:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 212, 170, 0.5);
        }

        .btn-outline-light {
            background: transparent;
            color: var(--dark);
            border: 3px solid var(--gray);
            backdrop-filter: blur(10px);
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
        }

        /* ========== QUICK ACTIONS ========== */
        .quick-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 35px;
            animation: fadeIn 0.6s ease-out 0.3s both;
        }

        .quick-action-btn {
            padding: 16px 28px;
            border-radius: var(--radius-lg);
            background: var(--light);
            border: 2px solid var(--light-gray);
            color: var(--dark);
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
            flex: 1;
            min-width: 180px;
            justify-content: center;
        }

        .quick-action-btn:hover {
            transform: translateY(-4px);
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            background: var(--primary-light);
        }

        .quick-action-btn i {
            font-size: 1.2rem;
            transition: transform 0.3s;
        }

        .quick-action-btn:hover i {
            transform: scale(1.2);
        }

        /* ========== FILTER SECTION ENHANCED ========== */
        .filter-section {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 35px;
            box-shadow: var(--shadow-xl);
            margin-bottom: 45px;
            border: 2px solid rgba(123, 104, 238, 0.1);
            animation: slideInRight 0.6s ease-out 0.2s both;
            position: relative;
            overflow: hidden;
        }

        .filter-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-1);
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .filter-header i {
            color: var(--primary);
            background: var(--primary-light);
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            box-shadow: var(--shadow-sm);
        }

        .filter-header h3 {
            font-weight: 900;
            font-size: 1.4rem;
            color: var(--dark);
            margin: 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            align-items: end;
        }

        @media (max-width: 1200px) {
            .filter-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
        }

        .filter-group {
            margin-bottom: 0;
            position: relative;
        }

        .filter-label {
            color: var(--dark);
            font-weight: 800;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: block;
            padding-right: 8px;
        }

        .filter-input,
        .filter-select {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: var(--shadow-sm);
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(123, 104, 238, 0.15);
            transform: translateY(-2px);
        }

        .filter-input {
            padding-left: 50px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='%237B68EE' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 20px center;
            background-size: 18px;
        }

        .filter-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%237B68EE' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 20px center;
            background-size: 16px;
            padding-left: 50px;
            cursor: pointer;
        }

        .filter-buttons {
            display: flex;
            gap: 15px;
            height: 100%;
            align-items: end;
        }

        .btn-filter {
            flex: 1;
            padding: 16px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 1rem;
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 56px;
            box-shadow: var(--shadow-sm);
        }

        .btn-filter:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .btn-filter-primary {
            background: var(--gradient-1);
            color: white;
            border: none;
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
        }

        .btn-filter-primary:hover {
            background: var(--gradient-1);
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(123, 104, 238, 0.4);
        }

        .btn-filter-reset {
            background: transparent;
            color: var(--gray);
            border: 2px solid var(--gray);
        }

        .btn-filter-reset:hover {
            background: var(--light-gray);
            color: var(--dark);
            border-color: var(--dark);
        }

        /* ========== VIEW TOGGLE ========== */
        .view-toggle {
            display: flex;
            background: var(--light);
            border-radius: var(--radius-lg);
            padding: 6px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-md);
            width: fit-content;
            margin-left: auto;
            animation: fadeIn 0.6s ease-out 0.4s both;
        }

        .view-btn {
            padding: 12px 24px;
            border-radius: var(--radius-md);
            background: transparent;
            border: none;
            color: var(--gray);
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .view-btn.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .view-btn:not(.active):hover {
            background: var(--light-gray);
            color: var(--dark);
        }

        /* ========== CLASSES GRID ENHANCED ========== */
        .classes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 30px;
            margin-bottom: 45px;
            animation: fadeIn 0.8s ease-out 0.5s both;
        }

        @media (max-width: 1200px) {
            .classes-grid {
                grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .classes-grid {
                grid-template-columns: 1fr;
            }
        }

        .class-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 0;
            box-shadow: var(--shadow-xl);
            border: 3px solid transparent;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .class-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .class-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 100%;
        }

        .class-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, transparent, rgba(255, 107, 157, 0.05));
            border-radius: 100% 0 0 0;
        }

        .class-ribbon {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 900;
            border-radius: var(--radius-full);
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(10px);
        }

        .ribbon-active {
            background: rgba(0, 212, 170, 0.2);
            color: #00D4AA;
            border: 2px solid rgba(0, 212, 170, 0.3);
        }

        .ribbon-archived {
            background: rgba(138, 141, 155, 0.2);
            color: var(--gray);
            border: 2px solid rgba(138, 141, 155, 0.3);
        }

        .class-header {
            padding: 30px 30px 25px;
            border-bottom: 2px solid var(--light-gray);
            position: relative;
            z-index: 2;
        }

        .class-title {
            font-weight: 900;
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            line-height: 1.4;
        }

        .class-title i {
            color: var(--primary);
            background: var(--primary-light);
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.4rem;
            box-shadow: var(--shadow-sm);
        }

        .class-subject {
            color: var(--gray);
            font-size: 1rem;
            font-weight: 700;
            padding-right: 65px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .class-subject i {
            color: var(--secondary);
        }

        .class-body {
            padding: 25px 30px;
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .class-description {
            color: var(--gray);
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            min-height: 72px;
            padding: 15px;
            background: var(--light-gray);
            border-radius: var(--radius-md);
            border-right: 4px solid var(--primary);
        }

        .class-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .meta-item {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 20px;
            text-align: center;
            transition: all 0.4s;
            border: 2px solid transparent;
        }

        .meta-item:hover {
            background: var(--primary-light);
            transform: translateY(-5px) scale(1.05);
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .meta-value {
            font-size: 2rem;
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 8px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .meta-label {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .class-code {
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.1), rgba(255, 107, 157, 0.1));
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
            border: 3px dashed rgba(123, 104, 238, 0.3);
            transition: all 0.3s;
            cursor: pointer;
        }

        .class-code:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .code-label {
            font-size: 0.95rem;
            color: var(--gray);
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .code-value {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--primary);
            font-family: 'Courier New', monospace;
            letter-spacing: 3px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: var(--radius-md);
            position: relative;
            overflow: hidden;
        }

        .code-value::after {
            content: 'کپی شد!';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: var(--radius-md);
        }

        .class-footer {
            padding: 25px 30px 30px;
            border-top: 2px solid var(--light-gray);
            position: relative;
            z-index: 2;
        }

        .class-actions {
            display: flex;
            gap: 15px;
        }

        .btn-class-action {
            flex: 1;
            padding: 16px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.4s;
            text-decoration: none;
            border: 3px solid transparent;
            min-height: 52px;
            position: relative;
            overflow: hidden;
        }

        .btn-class-action:active {
            transform: scale(0.95);
        }

        .btn-exam {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
        }

        .btn-exam:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 15px 25px rgba(123, 104, 238, 0.4);
        }

        .btn-exam::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-exam:active::before {
            width: 300px;
            height: 300px;
        }

        .btn-details {
            background: transparent;
            color: var(--dark);
            border: 3px solid var(--gray);
        }

        .btn-details:hover {
            background: var(--light-gray);
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }

        .btn-manage {
            background: var(--gradient-2);
            color: white;
            box-shadow: 0 8px 20px rgba(0, 212, 170, 0.3);
        }

        .btn-manage:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 15px 25px rgba(0, 212, 170, 0.4);
        }

        /* ========== EMPTY STATE ENHANCED ========== */
        .empty-state {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 80px 50px;
            text-align: center;
            box-shadow: var(--shadow-xl);
            border: 3px dashed rgba(123, 104, 238, 0.3);
            animation: fadeIn 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .empty-state::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-1);
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.1), rgba(0, 212, 170, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 3.2rem;
            color: var(--primary);
            animation: pulse 2s infinite;
            box-shadow: var(--shadow-lg);
            border: 3px solid rgba(123, 104, 238, 0.2);
        }

        .empty-title {
            font-weight: 900;
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .empty-description {
            color: var(--gray);
            font-size: 1.2rem;
            line-height: 1.8;
            max-width: 600px;
            margin: 0 auto 40px;
            font-weight: 500;
        }

        /* ========== PAGINATION ENHANCED ========== */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            animation: fadeIn 0.8s ease-out;
        }

        .pagination-custom {
            display: flex;
            gap: 12px;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        .page-item {
            display: flex;
        }

        .page-link {
            padding: 14px 22px;
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 800;
            text-decoration: none;
            border: 2px solid var(--light-gray);
            transition: all 0.4s;
            min-width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
        }

        .page-link:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .page-item.active .page-link {
            background: var(--gradient-1);
            color: white;
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(123, 104, 238, 0.3);
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* ========== FLOATING ACTIONS ========== */
        .floating-actions {
            position: fixed;
            bottom: 30px;
            left: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1000;
            animation: slideInLeft 0.6s ease-out 0.8s both;
        }

        .floating-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gradient-1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: var(--shadow-xl);
            border: none;
            cursor: pointer;
            transition: all 0.4s;
            position: relative;
            overflow: hidden;
        }

        .floating-btn:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 15px 30px rgba(123, 104, 238, 0.4);
        }

        .floating-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .floating-btn:active::after {
            width: 200px;
            height: 200px;
        }

        .floating-btn-tooltip {
            position: absolute;
            right: 70px;
            background: var(--dark);
            color: white;
            padding: 8px 16px;
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            font-weight: 700;
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.3s;
            white-space: nowrap;
            pointer-events: none;
            box-shadow: var(--shadow-md);
        }

        .floating-btn:hover .floating-btn-tooltip {
            opacity: 1;
            transform: translateX(0);
        }

        /* ========== TOAST NOTIFICATION ========== */
        .toast {
            position: fixed;
            bottom: 100px;
            left: 30px;
            background: var(--dark);
            color: white;
            padding: 20px 25px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            z-index: 1001;
            display: flex;
            align-items: center;
            gap: 15px;
            transform: translateX(-100px);
            opacity: 0;
            transition: all 0.4s;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 800;
            font-size: 1rem;
            margin-bottom: 4px;
        }

        .toast-message {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .classes-container {
                padding: 20px 15px 80px;
            }

            .hero-section {
                padding: 25px;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn-hero {
                width: 100%;
                min-width: unset;
            }

            .stats-header {
                gap: 15px;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-value {
                font-size: 1.8rem;
            }

            .filter-section {
                padding: 25px;
            }

            .filter-grid {
                gap: 15px;
            }

            .filter-buttons {
                flex-direction: column;
            }

            .class-card {
                margin-bottom: 20px;
            }

            .class-header,
            .class-body,
            .class-footer {
                padding: 25px;
            }

            .class-meta {
                grid-template-columns: 1fr;
            }

            .class-actions {
                flex-direction: column;
            }

            .empty-state {
                padding: 50px 25px;
            }

            .empty-title {
                font-size: 1.6rem;
            }

            .floating-actions {
                bottom: 20px;
                left: 20px;
            }

            .floating-btn {
                width: 55px;
                height: 55px;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                padding: 20px;
            }

            .hero-content h1 {
                font-size: 1.6rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .code-value {
                font-size: 1.4rem;
                letter-spacing: 2px;
            }

            .page-link {
                padding: 12px 16px;
                min-width: 40px;
            }

            .floating-actions {
                bottom: 15px;
                left: 15px;
            }

            .floating-btn {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
        }

        /* دکمه‌های لمسی بزرگ برای موبایل */
        .btn-hero,
        .btn-filter,
        .btn-class-action,
        .page-link,
        .quick-action-btn {
            min-height: 48px;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(123, 104, 238, 0.3);
            color: var(--dark);
        }

        /* اسکرول بار سفارشی */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-1);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
    </style>
@endpush

@section('content')
    <div class="classes-container">
        {{-- ========== STATS HEADER ========== --}}
        <div class="stats-header">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-value" id="totalClasses">{{ $classes->total() ?? 0 }}</div>
                <div class="stat-label">کل کلاس‌ها</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>{{ $classes->where('is_active', true)->count() ?? 0 }} فعال</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value" id="totalStudents">{{ $totalStudents ?? 0 }}</div>
                <div class="stat-label">دانش‌آموزان کل</div>
                <div class="stat-change positive">
                    <i class="fas fa-user-plus"></i>
                    <span>میانگین {{ $avgStudentsPerClass ?? 0 }} نفر</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-value" id="totalExams">{{ $totalExams ?? 0 }}</div>
                <div class="stat-label">آزمون‌های برگزار شده</div>
                <div class="stat-change positive">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ $activeExams ?? 0 }} فعال</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value" id="attendanceRate">{{ $avgAttendance ?? '0' }}%</div>
                <div class="stat-label">میانگین حضور</div>
                <div class="stat-change positive">
                    <i class="fas fa-check-circle"></i>
                    <span>+۵٪ نسبت به هفته قبل</span>
                </div>
            </div>
        </div>

        {{-- ========== HERO SECTION ========== --}}
        <div class="hero-section">
            <div class="hero-content">
                <h1>
                    <span class="hero-title-gradient">مدیریت کلاس‌های هوشمند</span>
                    🎓
                </h1>
                <p class="hero-subtitle">
                    به پنل مدیریت کلاس‌های هوشمند خوش آمدید! در اینجا می‌توانید تمام کلاس‌های خود را مدیریت کنید،
                    آزمون‌های جدید ایجاد کنید، عملکرد دانش‌آموزان را بررسی کرده و گزارش‌های تحلیلی دریافت نمایید.
                </p>
            </div>

            <div class="hero-actions">
                @if (Route::has('teacher.classes.create'))
                    <a href="{{ route('teacher.classes.create') }}" class="btn-hero btn-primary-grad" id="createClassBtn">
                        <i class="fas fa-plus-circle"></i>
                        ایجاد کلاس جدید
                    </a>
                @endif

                @if (Route::has('teacher.exams.index'))
                    <a href="{{ route('teacher.exams.index') }}" class="btn-hero btn-success-grad">
                        <i class="fas fa-file-alt"></i>
                        مشاهده آزمون‌ها
                    </a>
                @endif

                <a href="{{ route('teacher.index') }}" class="btn-hero btn-outline-light">
                    <i class="fas fa-home"></i>
                    بازگشت به داشبورد
                </a>
            </div>
        </div>

        {{-- ========== QUICK ACTIONS ========== --}}
        <div class="quick-actions">
            <a href="{{ route('teacher.classes.create') }}" class="quick-action-btn">
                <i class="fas fa-plus-circle"></i>
                کلاس جدید
            </a>
            <a href="#" class="quick-action-btn" onclick="showBulkActions()">
                <i class="fas fa-bolt"></i>
                اقدامات گروهی
            </a>
            <a href="#" class="quick-action-btn" onclick="exportClasses()">
                <i class="fas fa-download"></i>
                خروجی Excel
            </a>
            <a href="#" class="quick-action-btn" onclick="showArchivePanel()">
                <i class="fas fa-archive"></i>
                کلاس‌های آرشیو
            </a>
            <a href="#" class="quick-action-btn" onclick="showStatistics()">
                <i class="fas fa-chart-pie"></i>
                آمار و گزارش
            </a>
        </div>

        {{-- ========== FILTER SECTION ========== --}}
        <div class="filter-section">
            <div class="filter-header">
                <i class="fas fa-sliders-h"></i>
                <h3>فیلتر و جستجوی پیشرفته</h3>
            </div>

            <form method="GET" action="{{ route('teacher.classes.index') }}" class="filter-grid" id="filterForm">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        جستجوی کلاس
                    </label>
                    <input type="text" name="q" class="filter-input" placeholder="نام کلاس، پایه، موضوع یا کد..."
                        value="{{ request('q') }}" autocomplete="off">
                </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-graduation-cap"></i>
                        پایه تحصیلی
                    </label>
                    @php $grade = request('grade', 'all'); @endphp
                    <select name="grade" class="filter-select">
                        <option value="all" {{ $grade === 'all' ? 'selected' : '' }}>همه پایه‌ها</option>
                        <option value="7" {{ $grade === '7' ? 'selected' : '' }}>هفتم</option>
                        <option value="8" {{ $grade === '8' ? 'selected' : '' }}>هشتم</option>
                        <option value="9" {{ $grade === '9' ? 'selected' : '' }}>نهم</option>
                        <option value="10" {{ $grade === '10' ? 'selected' : '' }}>دهم</option>
                        <option value="11" {{ $grade === '11' ? 'selected' : '' }}>یازدهم</option>
                        <option value="12" {{ $grade === '12' ? 'selected' : '' }}>دوازدهم</option>
                        <option value="other" {{ $grade === 'other' ? 'selected' : '' }}>سایر</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-flag"></i>
                        وضعیت
                    </label>
                    @php $status = request('status', 'all'); @endphp
                    <select name="status" class="filter-select">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>همه کلاس‌ها</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>آرشیو شده</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>در انتظار</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-sort"></i>
                        مرتب‌سازی
                    </label>
                    @php $sort = request('sort', 'latest'); @endphp
                    <select name="sort" class="filter-select">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>جدیدترین</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>قدیمی‌ترین</option>
                        <option value="students" {{ $sort === 'students' ? 'selected' : '' }}>پر دانش‌آموزترین</option>
                        <option value="exams" {{ $sort === 'exams' ? 'selected' : '' }}>پر آزمون‌ترین</option>
                        <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>نام (صعودی)</option>
                        <option value="title_desc" {{ $sort === 'title_desc' ? 'selected' : '' }}>نام (نزولی)</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-filter"></i>
                        نمایش در صفحه
                    </label>
                    <select name="per_page" class="filter-select">
                        <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>۱۲ مورد</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>۲۴ مورد</option>
                        <option value="36" {{ request('per_page') == 36 ? 'selected' : '' }}>۳۶ مورد</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>۴۸ مورد</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>همه موارد</option>
                    </select>
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="btn-filter btn-filter-primary" id="applyFilter">
                        <i class="fas fa-sliders-h"></i>
                        اعمال فیلتر
                    </button>
                    <a href="{{ route('teacher.classes.index') }}" class="btn-filter btn-filter-reset">
                        <i class="fas fa-times"></i>
                        حذف فیلتر
                    </a>
                </div>
            </form>
        </div>

        {{-- ========== VIEW TOGGLE ========== --}}
        <div class="view-toggle">
            <button class="view-btn active" data-view="grid">
                <i class="fas fa-th-large"></i>
                نمایش شبکه‌ای
            </button>
            <button class="view-btn" data-view="list">
                <i class="fas fa-list"></i>
                نمایش لیستی
            </button>
            <button class="view-btn" data-view="compact">
                <i class="fas fa-grip-vertical"></i>
                نمایش فشرده
            </button>
        </div>

        {{-- ========== CLASSES LIST ========== --}}
        @php
            $classes = $classes ?? collect();
            $hasPaginator = method_exists($classes, 'total');
            $totalClasses = $hasPaginator ? $classes->total() : $classes->count();
        @endphp

        @if ($totalClasses == 0)
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-people-group"></i>
                </div>
                <h3 class="empty-title">هنوز کلاسی ایجاد نکرده‌اید!</h3>
                <p class="empty-description">
                    ایجاد کلاس اولین گام در مدیریت آموزشی هوشمند است. با ایجاد کلاس می‌توانید دانش‌آموزان را اضافه کرده،
                    آزمون‌های کلاسی برگزار کنید و پیشرفت تحصیلی را رصد نمایید.
                </p>
                @if (Route::has('teacher.classes.create'))
                    <a href="{{ route('teacher.classes.create') }}" class="btn-hero btn-primary-grad"
                        style="display: inline-flex;">
                        <i class="fas fa-plus-circle"></i>
                        ایجاد اولین کلاس
                    </a>
                @endif
            </div>
        @else
            <div class="classes-grid" id="classesGrid">
                @foreach ($classes as $index => $class)
                    @php
                        $studentsCount = $class->students_count ?? ($class->students->count() ?? 0);
                        $examsCount = $class->exams_count ?? 0;
                        $isActive = $class->is_active ?? true;
                        $gradeLabel = $class->grade ?? '—';
                        $code = $class->code ?? ($class->join_code ?? null);
                        $description = $class->description ?? null;
                        $subject = $class->subject ?? 'عمومی';
                        $createdAt = $class->created_at ?? now();
                        $daysAgo = $createdAt->diffInDays(now());
                        $isNew = $daysAgo < 7;
                    @endphp

                    <div class="class-card" style="animation-delay: {{ $index * 0.1 }}s">
                        @if ($isNew)
                            <div class="class-ribbon ribbon-active">
                                <i class="fas fa-star"></i>
                                جدید
                            </div>
                        @else
                            <div class="class-ribbon {{ $isActive ? 'ribbon-active' : 'ribbon-archived' }}">
                                <i class="fas {{ $isActive ? 'fa-bolt' : 'fa-archive' }}"></i>
                                {{ $isActive ? 'فعال' : 'آرشیو' }}
                            </div>
                        @endif

                        <div class="class-header">
                            <div class="class-title">
                                <i class="fas fa-chalkboard-teacher"></i>
                                {{ $class->title ?? ($class->name ?? 'کلاس بدون نام') }}
                                @if ($isNew)
                                    <span style="color: var(--warning); font-size: 0.9em;">✨</span>
                                @endif
                            </div>
                            <div class="class-subject">
                                <i class="fas fa-book"></i>
                                {{ $subject }}
                                <span style="margin: 0 10px">•</span>
                                پایه {{ $gradeLabel }}
                            </div>
                        </div>

                        <div class="class-body">
                            @if ($description)
                                <div class="class-description">
                                    {{ \Illuminate\Support\Str::limit($description, 150) }}
                                </div>
                            @else
                                <div class="class-description" style="color: var(--gray); font-style: italic;">
                                    <i class="fas fa-info-circle"></i>
                                    توضیحاتی برای این کلاس ثبت نشده است.
                                </div>
                            @endif

                            <div class="class-meta">
                                <div class="meta-item" data-tooltip="پایه تحصیلی کلاس">
                                    <div class="meta-value">
                                        <i class="fas fa-graduation-cap"></i>
                                        {{ $gradeLabel }}
                                    </div>
                                    <div class="meta-label">پایه</div>
                                </div>
                                <div class="meta-item" data-tooltip="تعداد دانش‌آموزان">
                                    <div class="meta-value">
                                        <i class="fas fa-users"></i>
                                        {{ $studentsCount }}
                                    </div>
                                    <div class="meta-label">دانش‌آموز</div>
                                </div>
                                <div class="meta-item" data-tooltip="تعداد آزمون‌های برگزار شده">
                                    <div class="meta-value">
                                        <i class="fas fa-file-alt"></i>
                                        {{ $examsCount }}
                                    </div>
                                    <div class="meta-label">آزمون</div>
                                </div>
                                <div class="meta-item" data-tooltip="تاریخ ایجاد">
                                    <div class="meta-value">
                                        <i class="fas fa-calendar"></i>
                                        {{ $daysAgo }}
                                    </div>
                                    <div class="meta-label">روز قبل</div>
                                </div>
                            </div>

                            @if ($code)
                                <div class="class-code" onclick="copyClassCode('{{ $code }}')"
                                    data-tooltip="کلیک برای کپی">
                                    <div class="code-label">
                                        <i class="fas fa-key"></i>
                                        کد ورود به کلاس
                                    </div>
                                    <div class="code-value">{{ $code }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="class-footer">
                            <div class="class-actions">
                                @if (Route::has('teacher.exams.create'))
                                    <a href="{{ route('teacher.exams.create', ['classroom_id' => $class->id]) }}"
                                        class="btn-class-action btn-exam">
                                        <i class="fas fa-plus-circle"></i>
                                        آزمون جدید
                                    </a>
                                @endif

                                @if (Route::has('teacher.classes.show'))
                                    <a href="{{ route('teacher.classes.show', $class->id) }}"
                                        class="btn-class-action btn-manage">
                                        <i class="fas fa-cogs"></i>
                                        مدیریت
                                    </a>
                                @endif

                                <button class="btn-class-action btn-details"
                                    onclick="showClassDetails({{ $class->id }})" data-class-id="{{ $class->id }}">
                                    <i class="fas fa-eye"></i>
                                    جزئیات
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ========== PAGINATION ========== --}}
            @if (method_exists($classes, 'hasPages') && $classes->hasPages())
                <div class="pagination-container">
                    <ul class="pagination-custom">
                        {{-- First Page --}}
                        @if (!$classes->onFirstPage())
                            <li class="page-item">
                                <a class="page-link" href="{{ $classes->url(1) }}" title="صفحه اول">
                                    <i class="fas fa-fast-backward"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Previous Page --}}
                        @if ($classes->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $classes->previousPageUrl() }}" rel="prev"
                                    title="صفحه قبل">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Numbers --}}
                        @php
                            $current = $classes->currentPage();
                            $last = $classes->lastPage();
                            $start = max($current - 2, 1);
                            $end = min($current + 2, $last);
                        @endphp

                        @if ($start > 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif

                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $current)
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $classes->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endfor

                        @if ($end < $last)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif

                        {{-- Next Page --}}
                        @if ($classes->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $classes->nextPageUrl() }}" rel="next"
                                    title="صفحه بعد">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        @endif

                        {{-- Last Page --}}
                        @if ($classes->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $classes->url($last) }}" title="صفحه آخر">
                                    <i class="fas fa-fast-forward"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
        @endif

        {{-- ========== FLOATING ACTIONS ========== --}}
        <div class="floating-actions">
            <button class="floating-btn" onclick="scrollToTop()" title="بالای صفحه">
                <i class="fas fa-arrow-up"></i>
                <span class="floating-btn-tooltip">برو بالا</span>
            </button>
            <button class="floating-btn" onclick="quickCreateExam()" title="آزمون سریع">
                <i class="fas fa-plus"></i>
                <span class="floating-btn-tooltip">آزمون سریع</span>
            </button>
            <button class="floating-btn btn-primary-grad" onclick="refreshPage()" title="بروزرسانی">
                <i class="fas fa-sync-alt"></i>
                <span class="floating-btn-tooltip">بروزرسانی</span>
            </button>
        </div>

        {{-- ========== TOAST NOTIFICATION ========== --}}
        <div class="toast" id="toast">
            <div class="toast-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title" id="toastTitle">موفق!</div>
                <div class="toast-message" id="toastMessage">عملیات با موفقیت انجام شد</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تنظیم انیمیشن‌های کارت‌ها
            const classCards = document.querySelectorAll('.class-card');
            classCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // ویبره برای موبایل
            if (navigator.vibrate) {
                const clickableItems = document.querySelectorAll(
                    '.btn-hero, .btn-filter, .btn-class-action, .page-link, .class-card, .floating-btn'
                );
                clickableItems.forEach(item => {
                    item.addEventListener('click', function() {
                        navigator.vibrate(25);
                    });
                });
            }

            // مدیریت view toggle
            const viewButtons = document.querySelectorAll('.view-btn');
            const classesGrid = document.getElementById('classesGrid');

            viewButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const view = this.dataset.view;
                    viewButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    classesGrid.className = 'classes-grid';
                    if (view === 'list') {
                        classesGrid.style.gridTemplateColumns = '1fr';
                        document.querySelectorAll('.class-card').forEach(card => {
                            card.style.height = 'auto';
                        });
                    } else if (view === 'compact') {
                        classesGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
                        document.querySelectorAll('.class-card').forEach(card => {
                            card.style.height = '400px';
                        });
                    }
                });
            });

            // کپی کد کلاس
            window.copyClassCode = function(code) {
                navigator.clipboard.writeText(code).then(() => {
                    showToast('کپی شد!', `کد ${code} در کلیپ‌بورد کپی شد.`);
                });
            };

            // نمایش توتیپ
            const tooltipElements = document.querySelectorAll('[data-tooltip]');
            tooltipElements.forEach(el => {
                el.addEventListener('mouseenter', function() {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.textContent = this.dataset.tooltip;
                    tooltip.style.cssText = `
                        position: absolute;
                        background: var(--dark);
                        color: white;
                        padding: 8px 12px;
                        border-radius: 8px;
                        font-size: 0.85rem;
                        z-index: 9999;
                        pointer-events: none;
                        transform: translateY(-100%);
                        opacity: 0;
                        transition: opacity 0.2s;
                        white-space: nowrap;
                    `;
                    this.appendChild(tooltip);

                    const rect = this.getBoundingClientRect();
                    tooltip.style.top = '-10px';
                    tooltip.style.left = '50%';
                    tooltip.style.transform = 'translateX(-50%) translateY(-100%)';

                    setTimeout(() => {
                        tooltip.style.opacity = '1';
                    }, 10);

                    this.addEventListener('mouseleave', function() {
                        tooltip.remove();
                    }, {
                        once: true
                    });
                });
            });

            // مدیریت فرم فیلتر
            const filterForm = document.getElementById('filterForm');
            const applyFilterBtn = document.getElementById('applyFilter');

            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    const originalText = applyFilterBtn.innerHTML;
                    applyFilterBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال جستجو...';
                    applyFilterBtn.disabled = true;

                    setTimeout(() => {
                        applyFilterBtn.innerHTML = originalText;
                        applyFilterBtn.disabled = false;
                    }, 1000);
                });

                // جستجوی لحظه‌ای
                const searchInput = filterForm.querySelector('input[name="q"]');
                let searchTimeout;

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            if (this.value.length >= 2 || this.value.length === 0) {
                                filterForm.submit();
                            }
                        }, 500);
                    });
                }
            }

            // بارگذاری آمار واقعی از API
            fetch('/api/teacher/class-stats')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('totalClasses').textContent = data.total_classes || 0;
                        document.getElementById('totalStudents').textContent = data.total_students || 0;
                        document.getElementById('totalExams').textContent = data.total_exams || 0;
                        document.getElementById('attendanceRate').textContent = (data.attendance_rate || 0) + '%';
                    }
                })
                .catch(err => console.log('خطا در دریافت آمار:', err));

            // نمایش تعداد نتایج
            updateResultsCount();
            setInterval(updateResultsCount, 30000); // آپدیت هر 30 ثانیه
        });

        // ========== توابع کمکی ==========

        function updateResultsCount() {
            const resultsCount = document.querySelectorAll('.class-card').length;
            const countElement = document.getElementById('resultsCount');

            if (!countElement) {
                const newElement = document.createElement('div');
                newElement.id = 'resultsCount';
                newElement.style.cssText = `
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: var(--gradient-1);
                    color: white;
                    padding: 12px 24px;
                    border-radius: 50px;
                    font-weight: 900;
                    font-size: 0.95rem;
                    z-index: 1000;
                    box-shadow: var(--shadow-xl);
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    animation: slideInRight 0.5s;
                    border: 2px solid white;
                `;
                document.body.appendChild(newElement);
            }

            const element = document.getElementById('resultsCount');
            element.innerHTML = `
                <i class="fas fa-layer-group"></i>
                ${resultsCount} کلاس یافت شد
                <span style="opacity: 0.8; font-size: 0.85em;">(${new Date().toLocaleTimeString('fa-IR')})</span>
            `;

            // پنهان شدن اتوماتیک
            setTimeout(() => {
                element.style.opacity = '0.7';
                element.style.transform = 'scale(0.95)';
            }, 5000);
        }

        function showToast(title, message) {
            const toast = document.getElementById('toast');
            document.getElementById('toastTitle').textContent = title;
            document.getElementById('toastMessage').textContent = message;

            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);

            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        }

        function showClassDetails(classId) {
            // در حالت واقعی اینجا درخواست AJAX به سرور می‌رود
            showToast('جزئیات کلاس', 'در حال بارگذاری اطلاعات کلاس...');

            // شبیه‌سازی بارگذاری
            setTimeout(() => {
                const modal = document.createElement('div');
                modal.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: white;
                    padding: 40px;
                    border-radius: 30px;
                    box-shadow: 0 30px 60px rgba(0,0,0,0.3);
                    z-index: 1002;
                    width: 90%;
                    max-width: 500px;
                    animation: scaleIn 0.3s ease;
                    border: 4px solid var(--primary);
                `;

                modal.innerHTML = `
                    <div style="text-align: center; margin-bottom: 30px;">
                        <div style="font-size: 4rem; color: var(--primary); margin-bottom: 20px;">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 style="margin-bottom: 15px; color: var(--dark); font-size: 1.5rem; font-weight: 900;">
                            جزئیات کلاس
                        </h3>
                        <p style="color: var(--gray); margin-bottom: 25px; font-size: 1.1rem; line-height: 1.6;">
                            این بخش در حال توسعه است. به زودی امکان مشاهده کامل جزئیات کلاس، دانش‌آموزان و گزارش‌ها فراهم می‌شود.
                        </p>
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <button onclick="this.parentElement.parentElement.remove(); document.getElementById('modalOverlay')?.remove();"
                                style="flex:1; padding: 18px; border: none; background: var(--light-gray); color: var(--dark); border-radius: 16px; font-weight: 900; font-size: 1.1rem; cursor: pointer;">
                            بستن
                        </button>
                        <button onclick="window.location.href='/teacher/classes/${classId}'"
                                style="flex:1; padding: 18px; border: none; background: var(--gradient-1); color: white; border-radius: 16px; font-weight: 900; font-size: 1.1rem; cursor: pointer;">
                            صفحه مدیریت
                        </button>
                    </div>
                `;

                document.body.appendChild(modal);

                const overlay = document.createElement('div');
                overlay.id = 'modalOverlay';
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0,0,0,0.7);
                    z-index: 1001;
                    animation: fadeIn 0.3s ease;
                    backdrop-filter: blur(5px);
                `;

                document.body.appendChild(overlay);
                overlay.addEventListener('click', () => {
                    modal.remove();
                    overlay.remove();
                });
            }, 500);
        }

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            showToast('برو بالا', 'در حال انتقال به بالای صفحه...');
        }

        function quickCreateExam() {
            showToast('آزمون سریع', 'منوی ایجاد آزمون سریع باز شد');
            // در اینجا می‌توانید یک منوی سریع برای ایجاد آزمون باز کنید
        }

        function refreshPage() {
            showToast('بروزرسانی', 'در حال دریافت آخرین اطلاعات...');
            setTimeout(() => {
                window.location.reload();
            }, 800);
        }

        function showBulkActions() {
            showToast('اقدامات گروهی', 'این قابلیت به زودی اضافه خواهد شد');
        }

        function exportClasses() {
            showToast('خروجی Excel', 'در حال تولید فایل خروجی...');
            // در اینجا درخواست export به سرور ارسال می‌شود
        }

        function showArchivePanel() {
            showToast('کلاس‌های آرشیو', 'در حال بارگذاری کلاس‌های آرشیو شده...');
            // اینجا می‌توانید یک پنل مودال برای آرشیو باز کنید
        }

        function showStatistics() {
            showToast('آمار و گزارش', 'در حال تولید گزارش تحلیلی...');
            // اینجا می‌توانید به صفحه آمار هدایت شوید
        }

        // اضافه کردن استایل‌های انیمیشن
        const style = document.createElement('style');
        style.textContent = `
            @keyframes scaleIn {
                from { transform: translate(-50%, -50%) scale(0.8); opacity: 0; }
                to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes slideInRight {
                from { transform: translateX(30px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            .tooltip {
                position: absolute;
                background: var(--dark);
                color: white;
                padding: 8px 12px;
                border-radius: 8px;
                font-size: 0.85rem;
                z-index: 9999;
                pointer-events: none;
                white-space: nowrap;
                animation: fadeIn 0.2s ease;
            }
        `;
        document.head.appendChild(style);
    </script>
@endpush