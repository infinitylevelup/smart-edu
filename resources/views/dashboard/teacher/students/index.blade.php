@extends('layouts.app')
@section('title', 'دانش‌آموزان من')

@push('styles')
    <style>
        /* تم کامل SmartEdu */
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

        .students-container {
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

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-15px) translateX(-10px);
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

        /* ========== HERO HEADER ========== */
        .hero-section {
            background: linear-gradient(135deg,
                    rgba(123, 104, 238, 0.1) 0%,
                    rgba(255, 107, 157, 0.1) 50%,
                    rgba(0, 212, 170, 0.1) 100%);
            border-radius: var(--radius-xl);
            padding: 35px 40px;
            margin-bottom: 35px;
            border: 2px solid rgba(123, 104, 238, 0.15);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(123, 104, 238, 0.08), transparent 70%);
            border-radius: 50%;
            animation: floaty 8s ease-in-out infinite;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0, 212, 170, 0.08), transparent 70%);
            border-radius: 50%;
            animation: floaty 10s ease-in-out infinite reverse;
        }

        .hero-content h1 {
            font-weight: 900;
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .hero-content h1::before {
            content: '';
            width: 8px;
            height: 50px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        .hero-subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            line-height: 1.8;
            max-width: 700px;
            margin: 0;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            margin-top: 25px;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .btn-hero {
            padding: 16px 32px;
            border-radius: var(--radius-lg);
            font-weight: 800;
            font-size: 1.05rem;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .btn-hero:active {
            transform: scale(0.97);
        }

        .btn-primary-grad {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 10px 25px rgba(123, 104, 238, 0.3);
        }

        .btn-primary-grad:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(123, 104, 238, 0.4);
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

        .btn-outline-secondary-grad {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
        }

        .btn-outline-secondary-grad:hover {
            background: var(--light-gray);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        /* ========== KPI STATS ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
            animation: fadeIn 0.6s ease-out;
            animation-delay: 0.1s;
            animation-fill-mode: both;
        }

        .kpi-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 28px;
            box-shadow: var(--shadow-lg);
            border: 2px solid transparent;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .kpi-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .kpi-card:nth-child(2)::before {
            background: linear-gradient(135deg, rgba(0, 212, 170, 0.08), transparent);
        }

        .kpi-card:nth-child(3)::before {
            background: linear-gradient(135deg, rgba(255, 209, 102, 0.08), transparent);
        }

        .kpi-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .kpi-title {
            color: var(--gray);
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-icon {
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

        .kpi-card:hover .kpi-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .kpi-value {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            line-height: 1;
        }

        .kpi-desc {
            font-size: 0.9rem;
            color: var(--gray);
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }

        /* ========== FILTER SECTION ========== */
        .filter-section {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 30px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 35px;
            border: 2px solid rgba(123, 104, 238, 0.08);
            animation: slideInLeft 0.5s ease-out;
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .filter-header i {
            color: var(--primary);
            background: var(--primary-light);
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .filter-header h3 {
            font-weight: 900;
            font-size: 1.3rem;
            color: var(--dark);
            margin: 0;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 15px;
            align-items: end;
        }

        @media (max-width: 992px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
        }

        .filter-group {
            margin-bottom: 0;
        }

        .filter-label {
            color: var(--gray);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
        }

        .filter-input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            transition: all 0.3s;
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 104, 238, 0.2);
        }

        .filter-select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            transition: all 0.3s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%237B68EE' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 18px center;
            background-size: 16px;
            padding-left: 45px;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 104, 238, 0.2);
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            height: 100%;
            align-items: end;
        }

        .btn-filter {
            flex: 1;
            padding: 14px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.95rem;
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 52px;
        }

        .btn-filter:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .btn-filter-primary {
            background: var(--gradient-1);
            color: white;
            border: none;
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.25);
        }

        .btn-filter-primary:hover {
            background: var(--gradient-1);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(123, 104, 238, 0.35);
        }

        .btn-filter-reset {
            background: transparent;
            color: var(--gray);
            border: 2px solid var(--gray);
        }

        .btn-filter-reset:hover {
            background: var(--light-gray);
            color: var(--dark);
        }

        /* ========== STUDENTS TABLE ========== */
        .students-table-container {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 0;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(123, 104, 238, 0.08);
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
            animation-delay: 0.2s;
            animation-fill-mode: both;
        }

        .table-header {
            padding: 25px 30px;
            border-bottom: 2px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-weight: 900;
            font-size: 1.3rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .table-title i {
            color: var(--primary);
            background: var(--primary-light);
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .students-count {
            background: var(--primary-light);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
        }

        .students-table thead {
            background: linear-gradient(90deg, rgba(123, 104, 238, 0.05), rgba(255, 107, 157, 0.05));
        }

        .students-table th {
            padding: 20px 25px;
            text-align: right;
            font-weight: 900;
            color: var(--dark);
            font-size: 0.95rem;
            border-bottom: 2px solid var(--light-gray);
            white-space: nowrap;
        }

        .students-table tbody tr {
            transition: all 0.3s;
            border-bottom: 1px solid var(--light-gray);
        }

        .students-table tbody tr:last-child {
            border-bottom: none;
        }

        .students-table tbody tr:hover {
            background: var(--primary-light);
            transform: translateX(-5px);
        }

        .students-table td {
            padding: 20px 25px;
            vertical-align: middle;
            font-weight: 700;
            color: var(--dark);
        }

        .student-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .student-name {
            font-weight: 900;
            font-size: 1.05rem;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .student-id {
            color: var(--gray);
            font-size: 0.85rem;
            font-weight: 700;
        }

        .student-email {
            color: var(--dark);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .student-classes {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            max-width: 300px;
        }

        .class-badge {
            background: rgba(0, 212, 170, 0.15);
            color: #00D4AA;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .class-badge-more {
            background: rgba(138, 141, 155, 0.15);
            color: var(--gray);
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 900;
        }

        .student-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 900;
            white-space: nowrap;
        }

        .status-active {
            background: rgba(0, 212, 170, 0.15);
            color: #00D4AA;
        }

        .status-inactive {
            background: rgba(255, 209, 102, 0.15);
            color: #FFD166;
        }

        .student-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            min-width: 200px;
        }

        .btn-action {
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid transparent;
            min-width: 90px;
            justify-content: center;
        }

        .btn-action:active {
            transform: scale(0.95);
        }

        .btn-profile {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-profile:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .btn-classes-dropdown {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-classes-dropdown:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            padding: 60px 30px;
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        .empty-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.1), rgba(0, 212, 170, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.8rem;
            color: var(--primary);
            animation: pulse 2s infinite;
        }

        .empty-title {
            font-weight: 900;
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .empty-description {
            color: var(--gray);
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        /* ========== PAGINATION ========== */
        .pagination-container {
            display: flex;
            justify-content: center;
            padding: 25px 30px;
            border-top: 2px solid var(--light-gray);
            animation: fadeIn 0.6s ease-out;
        }

        .pagination-custom {
            display: flex;
            gap: 10px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .page-item {
            display: flex;
        }

        .page-link {
            padding: 12px 20px;
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 800;
            text-decoration: none;
            border: 2px solid var(--light-gray);
            transition: all 0.3s;
            min-width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-link:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .page-item.active .page-link {
            background: var(--gradient-1);
            color: white;
            border-color: var(--primary);
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .students-table {
                display: block;
                overflow-x: auto;
            }

            .students-table thead {
                display: none;
            }

            .students-table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 2px solid var(--light-gray);
                border-radius: var(--radius-lg);
                padding: 20px;
            }

            .students-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0;
                border-bottom: 1px dashed var(--light-gray);
            }

            .students-table td:last-child {
                border-bottom: none;
                padding-top: 20px;
                justify-content: flex-end;
            }

            .students-table td::before {
                content: attr(data-label);
                font-weight: 900;
                color: var(--gray);
                font-size: 0.9rem;
                min-width: 100px;
                text-align: left;
            }

            .student-actions {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .students-container {
                padding: 15px 10px 60px;
            }

            .hero-section {
                padding: 25px;
            }

            .hero-content h1 {
                font-size: 1.8rem;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn-hero {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .kpi-card {
                padding: 22px;
            }

            .kpi-value {
                font-size: 2.2rem;
            }

            .filter-section {
                padding: 20px;
            }

            .filter-grid {
                gap: 12px;
            }

            .filter-buttons {
                flex-direction: column;
            }

            .table-header {
                padding: 20px;
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .empty-state {
                padding: 40px 20px;
            }

            .empty-icon {
                width: 80px;
                height: 80px;
                font-size: 2.2rem;
            }

            .empty-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .kpi-value {
                font-size: 2rem;
            }

            .student-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .btn-action {
                min-width: auto;
                padding: 8px 12px;
                font-size: 0.8rem;
            }

            .student-status {
                padding: 8px 12px;
                font-size: 0.8rem;
            }

            .page-link {
                padding: 10px 15px;
                min-width: 40px;
            }
        }

        /* دکمه‌های لمسی بزرگ */
        .btn-hero,
        .btn-filter,
        .btn-action,
        .page-link {
            min-height: 44px;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(123, 104, 238, 0.2);
            color: var(--dark);
        }
    </style>
@endpush

@section('content')
    <div class="students-container">
        {{-- ========== HERO SECTION ========== --}}
        <div class="hero-section">
            <div class="hero-content">
                <h1>
                    <span
                        style="background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        دانش‌آموزان من
                    </span>
                    👨‍🎓
                </h1>
                <p class="hero-subtitle">
                    لیست همه‌ی دانش‌آموزان عضو کلاس‌های شما.
                    عملکرد، پیشرفت و وضعیت تحصیلی هر دانش‌آموز را زیر نظر داشته باشید.
                </p>
            </div>

            <div class="hero-actions">
                <a href="{{ route('teacher.classes.index') }}" class="btn-hero btn-outline-secondary-grad">
                    <i class="fas fa-people-group"></i>
                    مدیریت کلاس‌ها
                </a>
                <button type="button" class="btn-hero btn-primary-grad" data-bs-toggle="modal" data-bs-target="#helpModal">
                    <i class="fas fa-question-circle"></i>
                    راهنمای استفاده
                </button>
            </div>
        </div>

        {{-- ========== KPI STATS ========== --}}
        <div class="stats-grid">
            @php
                $totalStudents = isset($students)
                    ? (method_exists($students, 'total')
                        ? $students->total()
                        : $students->count())
                    : 0;

                $totalClasses = isset($classrooms) ? $classrooms->count() : 0;

                $studentsCollection = isset($students)
                    ? (method_exists($students, 'items')
                        ? collect($students->items())
                        : collect($students))
                    : collect();

                $activeCount = $studentsCollection->filter(fn($s) => !empty($s->email_verified_at))->count();
            @endphp

            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-title">تعداد دانش‌آموزان</div>
                    <div class="kpi-icon" style="background: var(--gradient-1);">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="kpi-value">{{ $totalStudents }}</div>
                <p class="kpi-desc">اعضای تمامی کلاس‌های شما</p>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-title">تعداد کلاس‌ها</div>
                    <div class="kpi-icon" style="background: var(--gradient-2);">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
                <div class="kpi-value">{{ $totalClasses }}</div>
                <p class="kpi-desc">کلاس‌های فعال و در دسترس</p>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-title">کاربران فعال</div>
                    <div class="kpi-icon" style="background: var(--gradient-3);">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <div class="kpi-value">{{ $activeCount }}</div>
                <p class="kpi-desc">بر اساس تایید ایمیل و فعالیت</p>
            </div>
        </div>

        {{-- ========== FILTER SECTION ========== --}}
        <div class="filter-section">
            <div class="filter-header">
                <i class="fas fa-search"></i>
                <h3>جستجو و فیلتر پیشرفته</h3>
            </div>

            <form method="GET" class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">جستجو در دانش‌آموزان</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="q" value="{{ request('q') }}" class="filter-input"
                            placeholder="نام، ایمیل یا بخشی از مشخصات...">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">فیلتر بر اساس کلاس</label>
                    <select name="classroom_id" class="filter-select">
                        <option value="">همه کلاس‌ها</option>
                        @if (isset($classrooms))
                            @foreach ($classrooms as $c)
                                <option value="{{ $c->id }}" @selected(request('classroom_id') == $c->id)>
                                    {{ $c->title }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="btn-filter btn-filter-primary">
                        <i class="fas fa-sliders-h"></i>
                        اعمال فیلتر
                    </button>
                    <a href="{{ route('teacher.students.index') }}" class="btn-filter btn-filter-reset">
                        <i class="fas fa-times"></i>
                        حذف فیلتر
                    </a>
                </div>
            </form>
        </div>

        {{-- ========== STUDENTS TABLE ========== --}}
        @if (!isset($students) || $totalStudents == 0)
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="empty-title">هنوز دانش‌آموزی ندارید!</h3>
                <p class="empty-description">
                    هنوز هیچ دانش‌آموزی در کلاس‌های شما عضو نشده است.
                    ابتدا کلاس‌های خود را ایجاد کنید، سپس دانش‌آموزان می‌توانند با کد کلاس به آن‌ها ملحق شوند.
                </p>
                <a href="{{ route('teacher.classes.index') }}" class="btn-hero btn-primary-grad"
                    style="display: inline-flex;">
                    <i class="fas fa-people-group"></i>
                    رفتن به مدیریت کلاس‌ها
                </a>
            </div>
        @else
            <div class="students-table-container">
                <div class="table-header">
                    <h3 class="table-title">
                        <i class="fas fa-list-check"></i>
                        لیست دانش‌آموزان
                    </h3>
                    <div class="students-count">
                        <i class="fas fa-hashtag"></i>
                        {{ $totalStudents }} دانش‌آموز
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="students-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>دانش‌آموز</th>
                                <th>ایمیل</th>
                                <th>کلاس‌ها</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($students as $i => $student)
                                @php
                                    $index = method_exists($students, 'firstItem')
                                        ? $students->firstItem() + $i
                                        : $i + 1;
                                    $studentClassrooms = $student->classrooms ?? collect();
                                @endphp

                                <tr>
                                    <td data-label="شماره">
                                        <span style="color: var(--primary); font-weight: 900; font-size: 1.1rem;">
                                            {{ $index }}
                                        </span>
                                    </td>

                                    <td data-label="دانش‌آموز">
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                {{ mb_substr($student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="student-name">{{ $student->name }}</div>
                                                <div class="student-id">ID: {{ $student->id }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td data-label="ایمیل" class="student-email">
                                        {{ $student->email }}
                                    </td>

                                    <td data-label="کلاس‌ها">
                                        @if ($studentClassrooms->count())
                                            <div class="student-classes">
                                                @foreach ($studentClassrooms->take(3) as $c)
                                                    <span class="class-badge">
                                                        <i class="fas fa-chalkboard"></i>
                                                        {{ $c->title }}
                                                    </span>
                                                @endforeach
                                                @if ($studentClassrooms->count() > 3)
                                                    <span class="class-badge-more">
                                                        +{{ $studentClassrooms->count() - 3 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span style="color: var(--gray); font-size: 0.9rem;">بدون عضویت</span>
                                        @endif
                                    </td>

                                    <td data-label="وضعیت">
                                        @if (!empty($student->email_verified_at))
                                            <span class="student-status status-active">
                                                <i class="fas fa-check-circle"></i>
                                                فعال
                                            </span>
                                        @else
                                            <span class="student-status status-inactive">
                                                <i class="fas fa-clock"></i>
                                                نیمه‌فعال
                                            </span>
                                        @endif
                                    </td>

                                    <td data-label="عملیات">
                                        <div class="student-actions">
                                            <a href="{{ route('teacher.students.show', $student) }}"
                                                class="btn-action btn-profile">
                                                <i class="fas fa-user"></i>
                                                پروفایل
                                            </a>

                                            @if ($studentClassrooms->count())
                                                <div class="dropdown">
                                                    <button class="btn-action btn-classes-dropdown dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-chalkboard"></i>
                                                        کلاس‌ها
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @foreach ($studentClassrooms as $c)
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-2"
                                                                    href="{{ route('teacher.classes.show', $c) }}">
                                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                                    {{ $c->title }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ========== PAGINATION ========== --}}
                @if (method_exists($students, 'links'))
                    <div class="pagination-container">
                        {{ $students->withQueryString()->links('vendor.pagination.custom') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- ========== HELP MODAL ========== --}}
    <div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: var(--radius-xl); border: 3px solid var(--primary);">
                <div class="modal-header border-0" style="padding: 25px 30px 0;">
                    <h5 class="modal-title fw-bold"
                        style="color: var(--dark); font-size: 1.3rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-question-circle" style="color: var(--primary);"></i>
                        راهنمای صفحه دانش‌آموزان
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3 pb-1 px-30" style="padding: 25px 30px;">
                    <div class="help-list">
                        <div class="help-item d-flex align-items-start gap-3 mb-3">
                            <div style="color: var(--primary); font-size: 1.2rem;">
                                <i class="fas fa-search"></i>
                            </div>
                            <div>
                                <h6 style="font-weight: 900; color: var(--dark); margin-bottom: 5px;">جستجوی پیشرفته</h6>
                                <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">
                                    با جستجو می‌توانید دانش‌آموزان را بر اساس نام، ایمیل یا شناسه پیدا کنید.
                                </p>
                            </div>
                        </div>

                        <div class="help-item d-flex align-items-start gap-3 mb-3">
                            <div style="color: var(--primary); font-size: 1.2rem;">
                                <i class="fas fa-filter"></i>
                            </div>
                            <div>
                                <h6 style="font-weight: 900; color: var(--dark); margin-bottom: 5px;">فیلتر کلاس</h6>
                                <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">
                                    فیلتر کلاس، فقط دانش‌آموزان همان کلاس را نشان می‌دهد.
                                </p>
                            </div>
                        </div>

                        <div class="help-item d-flex align-items-start gap-3 mb-3">
                            <div style="color: var(--primary); font-size: 1.2rem;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h6 style="font-weight: 900; color: var(--dark); margin-bottom: 5px;">نمایش پروفایل</h6>
                                <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">
                                    روی «پروفایل» کلیک کنید تا نتایج آزمون‌ها و عملکرد دانش‌آموز را مشاهده کنید.
                                </p>
                            </div>
                        </div>

                        <div class="help-item d-flex align-items-start gap-3">
                            <div style="color: var(--primary); font-size: 1.2rem;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h6 style="font-weight: 900; color: var(--dark); margin-bottom: 5px;">دسترسی سریع</h6>
                                <p style="color: var(--gray); font-size: 0.95rem; line-height: 1.6;">
                                    از منوی کلاس‌ها می‌توانید سریع به کلاس مرتبط بروید.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0" style="padding: 0 30px 25px;">
                    <button type="button" class="btn-hero btn-outline-secondary-grad" style="width: 100%;"
                        data-bs-dismiss="modal">
                        <i class="fas fa-check"></i>
                        متوجه شدم
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ویبره برای موبایل
            if (navigator.vibrate) {
                const clickableItems = document.querySelectorAll(
                    '.btn-hero, .btn-filter, .btn-action, .page-link, .students-table tbody tr');
                clickableItems.forEach(item => {
                    item.addEventListener('click', function() {
                        navigator.vibrate(20);
                    });
                });
            }

            // افکت hover برای سطرهای جدول
            const tableRows = document.querySelectorAll('.students-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(10);
                    }
                });

                // کلیک روی سطر برای مشاهده پروفایل
                const profileBtn = row.querySelector('.btn-profile');
                if (profileBtn) {
                    row.addEventListener('click', function(e) {
                        if (!e.target.closest('.btn-action') && !e.target.closest('.dropdown')) {
                            window.location.href = profileBtn.href;
                        }
                    });
                }
            });

            // انیمیشن ورود المان‌ها
            const animateElements = () => {
                const elements = document.querySelectorAll('.students-table tbody tr');
                elements.forEach((el, i) => {
                    el.style.animationDelay = `${i * 0.05}s`;
                    el.style.animation = 'fadeIn 0.5s ease-out forwards';
                    el.style.opacity = '0';
                });
            };

            // اجرای انیمیشن بعد از لود صفحه
            setTimeout(animateElements, 300);

            // اعتبارسنجی فرم فیلتر
            const filterForm = document.querySelector('.filter-grid');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال جستجو...';
                    submitBtn.disabled = true;

                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 1500);
                });
            }

            // نمایش تعداد نتایج فیلتر شده
            const updateResultsCount = () => {
                const resultsCount = document.querySelectorAll('.students-table tbody tr').length;
                const countElement = document.querySelector('.students-count');
                if (countElement && resultsCount > 0) {
                    const searchQuery = document.querySelector('input[name="q"]').value;
                    const classFilter = document.querySelector('select[name="classroom_id"]').value;

                    if (searchQuery || classFilter) {
                        countElement.innerHTML =
                            `<i class="fas fa-filter"></i> ${resultsCount} دانش‌آموز یافت شد`;
                        countElement.style.background = 'var(--gradient-1)';
                        countElement.style.color = 'white';

                        // بازگشت به حالت عادی بعد از 5 ثانیه
                        setTimeout(() => {
                            countElement.innerHTML =
                                `<i class="fas fa-hashtag"></i> ${resultsCount} دانش‌آموز`;
                            countElement.style.background = 'var(--primary-light)';
                            countElement.style.color = 'var(--primary)';
                        }, 5000);
                    }
                }
            };

            // اجرای شمارش نتایج
            setTimeout(updateResultsCount, 500);
        });
    </script>
@endpush
