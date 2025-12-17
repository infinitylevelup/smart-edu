<style>
    .card-soft {
        border: 0;
        border-radius: 1.25rem;
        box-shadow: 0 8px 24px rgba(18, 38, 63, .08);
        background: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-soft:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(18, 38, 63, .12);
    }

    .chip {
        background: linear-gradient(135deg, #eef2ff, #dbeafe);
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.85rem;
        color: #4f46e5;
        border: 1px solid #c7d2fe;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .tiny {
        font-size: 0.85rem;
        line-height: 1.5;
    }

    .muted {
        color: #6b7280;
    }

    .hero {
        position: relative;
        overflow: hidden;
        border-radius: 1.5rem;
        background: linear-gradient(135deg, #f0f9ff, #f0fdf4);
        border: 2px dashed #d1d5db;
        backdrop-filter: blur(8px);
    }

    .hero-orb {
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        right: -80px;
        top: -80px;
        background: radial-gradient(circle, #0d6efd22, transparent 65%);
        animation: float-orb 6s ease-in-out infinite;
    }

    .hero-orb2 {
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        left: -60px;
        bottom: -60px;
        background: radial-gradient(circle, #10b98122, transparent 65%);
        animation: float-orb 8s ease-in-out infinite reverse;
    }

    .q-row {
        transition: all 0.25s ease;
        border-left: 4px solid transparent;
    }

    .q-row:hover {
        background: #f8fafc;
        border-left-color: #3b82f6;
        transform: translateX(4px);
    }

    .exam-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .type-public {
        color: #0d6efd;
        border-color: #0d6efd40;
        background: #0d6efd08;
    }

    .type-class {
        color: #10b981;
        border-color: #10b98140;
        background: #10b98108;
    }

    .type-class-single {
        color: #8b5cf6;
        border-color: #8b5cf640;
        background: #8b5cf608;
    }

    .type-class-comprehensive {
        color: #f59e0b;
        border-color: #f59e0b40;
        background: #f59e0b08;
    }

    .badge-status {
        font-size: 0.8rem;
        padding: 0.35rem 0.85rem;
        border-radius: 50px;
        font-weight: 600;
    }

    .table th {
        font-weight: 700;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        background: #f9fafb;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }

    .question-content {
        max-width: 520px;
        word-wrap: break-word;
        line-height: 1.6;
        color: #1f2937;
    }

    @keyframes float-orb {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-12px) scale(1.05); }
    }

    .btn-action {
        border-radius: 0.75rem;
        padding: 0.65rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid transparent;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: #6b7280;
        background: #fafafa;
        border-radius: 1rem;
        border: 2px dashed #d1d5db;
    }
</style>