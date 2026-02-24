@extends('layout.master-mini')

@section('content')
    <!-- CACHE BUSTER: {{ time() }} -->
    <style>
        /* ============= FORCE SIDEBAR STYLES ============= */
        div.guide-sidebar {
            position: sticky !important;
            top: 100px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 20px !important;
            padding: 32px !important;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3) !important;
            border: 5px solid rgba(255, 255, 255, 0.2) !important;
            max-height: calc(100vh - 120px) !important;
            overflow-y: auto !important;
        }
        
        div.guide-sidebar * {
            color: white !important;
        }
        
        div.guide-sidebar h5 {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
        }
        
        div.guide-sidebar ul {
            list-style: none !important;
            list-style-type: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        div.guide-sidebar ul li {
            list-style: none !important;
            list-style-type: none !important;
            display: block !important;
        }
        
        div.guide-sidebar ul li::before,
        div.guide-sidebar ul li::marker,
        div.guide-sidebar ul li::after {
            content: none !important;
            display: none !important;
        }
        
        div.guide-sidebar a {
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            text-decoration: none !important;
        }
        
        div.guide-sidebar a:hover {
            background: rgba(255, 255, 255, 0.25) !important;
        }
        
        div.guide-sidebar i {
            color: white !important;
        }
        
        .guide-page-wrapper {
            padding-top: 90px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .guide-breadcrumb {
            background: white;
            padding: 15px 0;
            margin: 0;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        }

        .guide-hero {
            background: white;
            padding: 0;
            margin: 0 0 30px 0;
        }
        
        .hero-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 45px 40px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            border: 4px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .hero-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(10%, 10%) scale(1.1); }
        }

        .hero-card h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 18px;
            color: white;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .hero-card h1 i {
            font-size: 2.5rem;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }

        .hero-card p {
            font-size: 1.18rem;
            margin-bottom: 0;
            color: white;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            font-weight: 500;
            line-height: 1.6;
        }
        
        .hero-card .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.25);
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 15px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        /* قائمة جانبية محسّنة */
        .guide-sidebar {
            position: sticky !important;
            top: 100px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 20px !important;
            padding: 32px !important;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3) !important;
            border: 5px solid rgba(255, 255, 255, 0.2) !important;
            max-height: calc(100vh - 120px) !important;
            overflow-y: auto !important;
            position: relative !important;
        }
        
        .guide-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
            border-radius: 16px;
            z-index: 0;
        }
        
        /* Custom Scrollbar للقائمة الجانبية */
        .guide-sidebar::-webkit-scrollbar {
            width: 8px;
        }
        
        .guide-sidebar::-webkit-scrollbar-track {
            background: rgba(102, 126, 234, 0.08);
            border-radius: 10px;
        }
        
        .guide-sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        .guide-sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #764ba2 0%, #667eea 100%);
        }

        .guide-sidebar h5 {
            color: white !important;
            font-size: 1.3rem !important;
            font-weight: 800 !important;
            margin-bottom: 26px !important;
            padding: 18px 20px !important;
            background: rgba(255, 255, 255, 0.15) !important;
            border-radius: 14px !important;
            display: flex !important;
            align-items: center !important;
            gap: 14px !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15) !important;
            position: relative !important;
            z-index: 1 !important;
            backdrop-filter: blur(10px) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
        }

        .guide-sidebar h5 i {
            font-size: 1.6rem !important;
            color: white !important;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3)) !important;
        }

        .guide-sidebar ul {
            list-style: none !important;
            list-style-type: none !important;
            padding: 0 !important;
            margin: 0 !important;
            padding-right: 0 !important;
            margin-right: 0 !important;
        }

        .guide-sidebar ul li {
            margin-bottom: 10px;
            list-style: none !important;
            list-style-type: none !important;
            display: block !important;
        }
        
        .guide-sidebar ul li::before {
            content: none !important;
            display: none !important;
        }
        
        .guide-sidebar ul li::marker {
            content: none !important;
            display: none !important;
        }
        
        .guide-sidebar ul li::after {
            content: none !important;
            display: none !important;
        }

        .guide-sidebar ul li a {
            display: flex !important;
            align-items: center !important;
            color: white !important;
            text-decoration: none !important;
            padding: 14px 18px !important;
            border-radius: 12px !important;
            font-size: 0.98rem !important;
            font-weight: 600 !important;
            transition: all 0.35s ease !important;
            border: 2px solid transparent !important;
            gap: 14px !important;
            background: rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
            position: relative !important;
            z-index: 1 !important;
            backdrop-filter: blur(5px) !important;
        }

        .guide-sidebar ul li a i {
            font-size: 1.35rem !important;
            color: white !important;
            min-width: 28px !important;
            transition: all 0.3s ease !important;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)) !important;
        }

        .guide-sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            transform: translateX(-8px) scale(1.03);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .guide-sidebar ul li a:hover i {
            color: white;
            transform: scale(1.2) rotate(8deg);
        }

        .guide-sidebar ul li a.active {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            transform: translateX(-6px);
        }

        .guide-sidebar ul li a.active i {
            color: white;
            transform: scale(1.15);
        }

        .guide-content {
            background: white;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
        }

        .guide-section {
            scroll-margin-top: 120px;
            padding: 35px 0;
            border-bottom: 2px solid #f0f0f0;
        }

        .guide-section:first-of-type {
            padding-top: 0;
        }

        .guide-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .guide-section h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 1.85rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .guide-section h2 i {
            font-size: 2rem;
            color: #764ba2;
        }

        .guide-section .lead {
            font-size: 1.12rem;
            color: #6c757d;
            margin-bottom: 25px;
            line-height: 1.75;
            padding: 18px 22px;
            background: #f8f9fa;
            border-right: 4px solid #667eea;
            border-radius: 8px;
        }

        .step-card {
            background: white;
            border: 3px solid #e9ecef;
            border-radius: 14px;
            padding: 28px;
            margin-bottom: 20px;
            transition: all 0.4s ease;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
        }
        
        .step-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .step-card:hover {
            border-color: #667eea;
            box-shadow: 0 8px 28px rgba(102, 126, 234, 0.25);
            transform: translateY(-6px);
        }
        
        .step-card:hover::before {
            opacity: 1;
        }

        .step-card h4 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 1.25rem;
        }

        .step-card h4 i {
            color: #764ba2;
            font-size: 1.7rem;
        }
        
        .step-card p {
            color: #6c757d;
            line-height: 1.8;
            margin-bottom: 0;
            font-size: 1.02rem;
        }
        
        .step-card ul {
            margin-top: 15px;
            padding-right: 20px;
        }
        
        .step-card ul li {
            margin-bottom: 8px;
            line-height: 1.7;
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            font-weight: 700;
            font-size: 1.3rem;
            margin-left: 16px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .faq-item {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .faq-item:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }

        .faq-item.active {
            border-color: #667eea;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.15);
        }

        .faq-question {
            padding: 18px 22px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: #495057;
            background: white;
            transition: all 0.3s;
        }

        .faq-question:hover {
            background: #f8f9fa;
            color: #667eea;
        }

        .faq-item.active .faq-question {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .faq-question i {
            font-size: 1.3rem;
            transition: transform 0.3s;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-answer {
            padding: 0 22px 20px 22px;
            display: none;
            color: #6c757d;
            line-height: 1.7;
            background: #f8f9fa;
        }

        .faq-item.active .faq-answer {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .calculator-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            padding: 32px;
            margin: 30px 0;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.35);
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .calculator-box h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .calculator-box h3 i {
            font-size: 2rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .calculator-box p {
            opacity: 0.95;
            margin-bottom: 25px;
            font-size: 1.05rem;
        }

        .calculator-box .form-group {
            margin-bottom: 20px;
        }

        .calculator-box label {
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
            font-size: 1.05rem;
        }

        .calculator-box .form-control {
            background: white;
            border: 3px solid rgba(255, 255, 255, 0.5);
            color: #495057;
            font-weight: 600;
            padding: 12px 16px;
            outline: none;
        }

        .calculator-box .btn {
            background: white;
            color: #667eea;
            font-weight: 700;
            border: none;
            padding: 13px 30px;
            margin-top: 12px;
            border-radius: 10px;
            font-size: 1.05rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .calculator-box .btn:hover {
            background: #764ba2;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }
        
        .calculator-box .btn i {
            margin-left: 8px;
            font-size: 1.2rem
            margin-top: 10px;
        }

        .calculator-box .btn:hover {
            background: #764ba2;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 16px;
            margin: 25px 0;
            box-shadow: 0 8px 28px rgba(102, 126, 234, 0.2);
            border: 4px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #667eea 0%, #764ba2 100%) border-box;
        }

        .video-wrapper:hover {
            box-shadow: 0 12px 36px rgba(102, 126, 234, 0.3);
            transform: scale(1.01);
            transition: all 0.4s ease;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;6px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.12);
            margin: 30px 0;
            border: 3px solid #e9ecef;
        }

        .fee-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 22px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .fee-table td {
            padding: 16px 22px;
            border-bottom: 1px solid #e9ecef;
            font-size: 1rem;
            color: #495057;
            font-weight: 500;
        }

        .fee-table tbody tr {
            transition: all 0.3s ease;
        }

        .fee-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.04) 0%, rgba(118, 75, 162, 0.04) 100%);
            transform: scale(1.01)
        .fee-table td {
            padding: 14px 18px;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.95rem;
            color: #495057;
        }

        .fee-table tbody tr:hover {
            background: #f8f9fa;
        }

        .fee-table tbody tr:last-child td {
            border-bottom: none;
        }

        .risk-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.88rem;
            font-weight: 600;
            margin: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .risk-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .risk-badge i {
            margin-left: 6px;
            font-size: 1rem;
        }

        .risk-low {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 2px solid #b1dfbb;
        }

        .risk-medium {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 2px solid #ffd93d;
        }

        .risk-high {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 2px solid #f5b7bd;
        }

        .timeline {
            position: relative;
            padding: 35px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            right: 50%;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
        }

        .timeline-item:nth-child(odd) {
            flex-direction: row;
            text-align: left;
        }

        .timeline-item:nth-child(even) {
            flex-direction: row-reverse;
            text-align: right;
        }

        .timeline-content {
            width: 45%;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .timeline-content:hover {
            border-color: #667eea;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2);
            transform: translateY(-3px);
        }

        .timeline-content h4 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .timeline-dot {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            position: absolute;
            right: calc(50% - 12px);
            box-shadow: 0 0 0 6px rgba(102, 126, 234, 0.2);
            z-index: 1;
        }

        .breadcrumb-custom {
            background: transparent;
            padding: 10px 0;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-custom .breadcrumb-item+.breadcrumb-item::before {
            content: "‹";
            margin: 0 10px;
        }

        .breadcrumb-custom a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb-custom a:hover {
            color: #667eea;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .guide-sidebar {
                position: static;
                max-height: none;
                margin-bottom: 30px;
                border-width: 2px;
            }
            
            .guide-sidebar h5 {
                font-size: 1.08rem;
                padding: 12px;
            }
            
            .guide-sidebar ul li a {
                font-size: 0.9rem;
                padding: 11px 13px;
            }
            
            .hero-card {
                padding: 35px 30px;
            }
            
            .hero-card h1 {
                font-size: 1.8rem;
            }
            
            .hero-card p {
                font-size: 1.05rem;
            }
        }
        
        @media (max-width: 768px) {
            .guide-page-wrapper {
                padding-top: 75px;
            }

            .hero-card {
                padding: 30px 25px;
                border-radius: 16px;
            }

            .hero-card h1 {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .hero-card h1 i {
                font-size: 2rem;
            }

            .hero-card p {
                font-size: 0.98rem;
                text-align: center;
            }
            
            .hero-card .hero-badge {
                font-size: 0.85rem;
                padding: 6px 16px;
            }

            .guide-section h2 {
                font-size: 1.45rem;
            }
            
            .guide-content {
                padding: 25px 20px;
            }
            
            .guide-sidebar {
                padding: 20px;
            }
            
            .guide-sidebar ul li a {
                font-size: 0.85rem;
                padding: 9px 10px;
                gap: 8px;
            }
            
            .guide-sidebar ul li a i {
                font-size: 1.1rem;
            }

            .timeline-content {
                width: 100%;
            }

            .timeline::before {
                right: 10px;
            }

            .timeline-dot {
                right: 1px;
            }

            .timeline-item {
                flex-direction: column !important;
                text-align: center !important;
            }
        }

        /* تحسينات إضافية للـ Cards */
        .card.border-0.shadow-sm {
            border: 3px solid #e9ecef !important;
            border-radius: 14px;
            transition: all 0.35s ease;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.07) !important;
            height: 100%;
        }

        .card.border-0.shadow-sm:hover {
            border-color: #667eea !important;
            box-shadow: 0 8px 28px rgba(102, 126, 234, 0.2) !important;
            transform: translateY(-5px);
        }

        .card.border-0.shadow-sm .card-body h5 {
            font-weight: 700;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
        }

        .card.border-0.shadow-sm .card-body h5 i {
            font-size: 1.5rem;
        }

        .card.border-0.shadow-sm .card-body ul {
            margin-bottom: 0;
            padding-right: 20px;
        }

        .card.border-0.shadow-sm .card-body ul li {
            padding: 10px 0;
            line-height: 1.7;
            font-size: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .card.border-0.shadow-sm .card-body ul li:last-child {
            border-bottom: none;
        }
    </style>

    <!-- Wrapper للصفحة كاملة -->
    <div class="guide-page-wrapper">

        <!-- Breadcrumbs -->
        <div class="guide-breadcrumb">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="background: transparent; padding: 0; font-size: 0.9rem;">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}"
                                style="color: #6c757d; text-decoration: none;">{{ __('الرئيسية') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('الدليل الشامل') }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="guide-hero">
            <div class="container">
                <div class="hero-card">
                    <h1>
                        <i class="mdi mdi-book-open-page-variant"></i>
                        {{ __('الدليل الشامل') }}
                    </h1>
                    
                    <!-- كارد مميز للوصف -->
                    <div style="background: linear-gradient(135deg, #1A5F3F 0%, #2D7A56 100%); border-radius: 20px; padding: 40px; margin: 25px 0; box-shadow: 0 15px 50px rgba(26, 95, 63, 0.3); border: 3px solid rgba(212, 175, 55, 0.4); position: relative; overflow: hidden; text-align: center;">
                        <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, transparent 70%); border-radius: 50%;"></div>
                        <div style="position: absolute; bottom: -30px; left: -30px; width: 120px; height: 120px; background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%); border-radius: 50%;"></div>
                        <div style="position: relative; z-index: 1;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 15px; margin-bottom: 20px;">
                                <i class="mdi mdi-information" style="font-size: 3rem; color: #D4AF37;"></i>
                                <h3 style="color: white; margin: 0; font-size: 1.6rem; font-weight: 700; max-width: 800px;">{{ __('دليلك الكامل لاستخدام منصة سهمي للاستثمار العقاري') }}</h3>
                            </div>
                            
                            <!-- زر تحميل PDF -->
                            <a href="{{ route('guide.pdf') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 12px; background: rgba(212, 175, 55, 0.2); border: 2px solid #D4AF37; color: #D4AF37; padding: 14px 30px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 1.05rem; transition: all 0.3s ease; box-shadow: 0 5px 20px rgba(212, 175, 55, 0.3);" onmouseover="this.style.background='#D4AF37'; this.style.color='#1A5F3F'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.5)';" onmouseout="this.style.background='rgba(212, 175, 55, 0.2)'; this.style.color='#D4AF37'; this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(212, 175, 55, 0.3)';">
                                <i class="mdi mdi-file-pdf-box" style="font-size: 1.5rem;"></i>
                                <span>{{ __('تحميل الدليل الشامل PDF') }}</span>
                                <i class="mdi mdi-download" style="font-size: 1.3rem;"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="hero-badge">
                        <i class="mdi mdi-shield-check"></i> {{ __('شامل ومفصل لجميع الخطوات') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="container my-4">
            <div class="row">
                
                <!-- القائمة الجانبية -->
                <div class="col-lg-3 mb-4">
                    <div class="guide-sidebar" style="background: linear-gradient(135deg, #1A5F3F 0%, #2D7A56 100%) !important; border: 5px solid rgba(212, 175, 55, 0.3) !important; border-radius: 20px !important; padding: 32px !important; box-shadow: 0 10px 40px rgba(26, 95, 63, 0.4) !important;">
                        <h5 style="color: white !important; background: rgba(212, 175, 55, 0.2) !important; padding: 18px 20px !important; border-radius: 14px !important; border: 2px solid rgba(212, 175, 55, 0.4) !important;"><i class="mdi mdi-format-list-bulleted" style="color: #D4AF37 !important;"></i> {{ __('المحتويات') }}</h5>
                        <ul style="list-style: none !important; padding: 0 !important; margin: 0 !important;">
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#what-is" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-home-city" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('ما هي الأسهم العقارية؟') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#how-market" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-chart-line" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('كيف يعمل السوق؟') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#registration" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-account-plus" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('التسجيل') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#kyc" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-shield-check" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('التحقق من الهوية') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#deposit" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-cash-plus" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('الإيداع') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#browse" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-magnify" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('تصفح الفرص') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#buying" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-cart" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('شراء الأسهم') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#profits" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-cash-multiple" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('الأرباح') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#secondary" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-swap-horizontal" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('السوق الثانوي') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#fees" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-currency-usd" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('الرسوم') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#risks" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-alert" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('المخاطر') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#calculator" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-calculator" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('الحاسبة') }}</a></li>
                            <li style="list-style: none !important; margin-bottom: 10px !important;"><a href="#faq" style="display: flex !important; align-items: center !important; gap: 14px !important; background: rgba(255, 255, 255, 0.1) !important; color: white !important; padding: 14px 18px !important; border-radius: 12px !important; text-decoration: none !important; transition: all 0.3s ease !important;" onmouseover="this.style.background='rgba(212, 175, 55, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'"><i class="mdi mdi-help-circle" style="color: #D4AF37 !important; font-size: 1.35rem !important;"></i> {{ __('الأسئلة الشائعة') }}</a></li>
                        </ul>
                    </div>
                </div>

                <!-- المحتوى -->
                <div class="col-lg-9">
                    <div class="guide-content">

                    <!-- ما هي الأسهم العقارية؟ -->
                    <section id="what-is" class="guide-section">
                        <h2><i class="mdi mdi-home-city"></i> {{ __('ما هي الأسهم العقارية؟') }}</h2>
                        <p class="lead">
                            {{ __('الأسهم العقارية هي وسيلة استثمارية تتيح لك امتلاك حصص في مشاريع عقارية دون الحاجة لرأس مال كبير.') }}
                        </p>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="text-primary"><i class="mdi mdi-check-circle"></i> {{ __('المزايا') }}
                                        </h5>
                                        <ul>
                                            <li>{{ __('رأس مال صغير للبدء') }}</li>
                                            <li>{{ __('تنويع المحفظة الاستثمارية') }}</li>
                                            <li>{{ __('عوائد دورية من الإيجارات') }}</li>
                                            <li>{{ __('إمكانية التداول في السوق الثانوي') }}</li>
                                            <li>{{ __('شفافية كاملة في العمليات') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="text-warning"><i class="mdi mdi-alert-circle"></i>
                                            {{ __('الاعتبارات') }}</h5>
                                        <ul>
                                            <li>{{ __('قد تتأثر العوائد بحالة السوق العقاري') }}</li>
                                            <li>{{ __('السيولة قد تكون محدودة في بعض الأوقات') }}</li>
                                            <li>{{ __('ليست استثماراً مضموناً 100%') }}</li>
                                            <li>{{ __('يتطلب فهماً جيداً للسوق') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- كيف يعمل السوق؟ -->
                    <section id="how-market" class="guide-section">
                        <h2><i class="mdi mdi-chart-line"></i> {{ __('كيف يعمل السوق العقاري؟') }}</h2>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="step-card">
                                    <h4><i class="mdi mdi-numeric-1-circle text-primary"></i>
                                        {{ __('السوق الأولي (Primary Market)') }}</h4>
                                    <p>{{ __('هو المكان الذي يتم فيه طرح الأسهم العقارية لأول مرة للاكتتاب العام. المستثمرون يشترون الأسهم مباشرة من الشركة المصدرة.') }}
                                    </p>
                                    <ul class="text-muted">
                                        <li>{{ __('سعر الإصدار الأولي محدد مسبقاً') }}</li>
                                        <li>{{ __('عدد محدود من الأسهم المتاحة') }}</li>
                                        <li>{{ __('فترة اكتتاب محددة') }}</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="step-card">
                                    <h4><i class="mdi mdi-numeric-2-circle text-success"></i>
                                        {{ __('السوق الثانوي (Secondary Market)') }}</h4>
                                    <p>{{ __('بعد انتهاء فترة الاكتتاب، يمكن للمستثمرين بيع وشراء الأسهم فيما بينهم في السوق الثانوي بأسعار السوق.') }}
                                    </p>
                                    <ul class="text-muted">
                                        <li>{{ __('الأسعار تتحدد بالعرض والطلب') }}</li>
                                        <li>{{ __('يمكن البيع والشراء في أي وقت') }}</li>
                                        <li>{{ __('سيولة أعلى للمستثمرين') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h5><i class="mdi mdi-information"></i> {{ __('العوائد المتوقعة') }}</h5>
                            <p class="mb-0">{{ __('العوائد تأتي من مصدرين رئيسيين:') }}</p>
                            <ol class="mb-0 mt-2">
                                <li><strong>{{ __('عوائد الإيجار:') }}</strong>
                                    {{ __('توزيعات دورية (شهرية/ربع سنوية/سنوية) من إيرادات تأجير العقار') }}</li>
                                <li><strong>{{ __('ربح رأس المال:') }}</strong>
                                    {{ __('عند بيع السهم بسعر أعلى من سعر الشراء في السوق الثانوي') }}</li>
                            </ol>
                        </div>

                        <div class="alert alert-warning">
                            <h5><i class="mdi mdi-alert"></i> {{ __('المخاطر الرئيسية') }}</h5>
                            <div class="d-flex flex-wrap">
                                <span
                                    class="risk-badge risk-low">{{ __('مخاطر منخفضة: عقارات في مواقع استراتيجية') }}</span>
                                <span class="risk-badge risk-medium">{{ __('مخاطر متوسطة: عقارات تحت الإنشاء') }}</span>
                                <span
                                    class="risk-badge risk-high">{{ __('مخاطر عالية: عقارات في مناطق غير مستقرة') }}</span>
                            </div>
                        </div>
                    </section>

                    <!-- فيديو تعريفي -->
                    <section id="video" class="guide-section">
                        <h2><i class="mdi mdi-play-circle"></i> {{ __('فيديو تعريفي عن المنصة') }}</h2>
                        <p class="lead">{{ __('شاهد هذا الفيديو القصير لفهم آلية عمل المنصة بشكل سريع ومبسط') }}</p>

                        <div class="row align-items-center">
                            <div class="col-lg-8 mb-4 mb-lg-0">
                                <div class="video-wrapper">
                                    <!-- استبدل الرابط التالي برابط فيديو حقيقي -->
                                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="فيديو تعريفي عن منصة سهمي"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border: 2px solid #667eea !important;">
                                    <div class="card-body">
                                        <h5 class="mb-4" style="color: #667eea;"><i class="mdi mdi-lightbulb"></i> {{ __('ما سيتعلمه في الفيديو') }}</h5>
                                        <ul class="list-unstyled" style="line-height: 2;">
                                            <li class="mb-3"><i class="mdi mdi-check-circle text-success" style="font-size: 1.3rem;"></i> {{ __('كيفية إنشاء حساب جديد') }}</li>
                                            <li class="mb-3"><i class="mdi mdi-check-circle text-success" style="font-size: 1.3rem;"></i> {{ __('خطوات التحقق من الهوية') }}</li>
                                            <li class="mb-3"><i class="mdi mdi-check-circle text-success" style="font-size: 1.3rem;"></i> {{ __('كيفية تصفح الفرص الاستثمارية') }}</li>
                                            <li class="mb-3"><i class="mdi mdi-check-circle text-success" style="font-size: 1.3rem;"></i> {{ __('طريقة شراء الأسهم') }}</li>
                                            <li><i class="mdi mdi-check-circle text-success" style="font-size: 1.3rem;"></i> {{ __('تتبع أرباحك ومحفظتك') }}</li>
                                        </ul>
                                        <div class="mt-4 pt-3" style="border-top: 2px solid #667eea;">
                                            <p class="mb-2" style="font-size: 0.9rem; color: #6c757d;"><i class="mdi mdi-clock-outline"></i> {{ __('المدة: 5 دقائق') }}</p>
                                            <p class="mb-0" style="font-size: 0.9rem; color: #6c757d;"><i class="mdi mdi-translate"></i> {{ __('متوفر مع ترجمة عربية') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- خطوات التسجيل -->
                    <section id="registration" class="guide-section">
                        <h2><i class="mdi mdi-account-plus"></i> {{ __('خطوات التسجيل خطوة بخطوة') }}</h2>

                        <div class="step-card">
                            <div class="d-flex align-items-start">
                                <span class="step-number">1</span>
                                <div>
                                    <h5>{{ __('انتقل إلى صفحة التسجيل') }}</h5>
                                    <p>{{ __('اضغط على زر "إنشاء حساب" في أعلى الصفحة') }}</p>
                                    <a href="{{ route('marketplace.register') }}"
                                        class="btn btn-sm btn-primary">{{ __('سجل الآن') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="d-flex align-items-start">
                                <span class="step-number">2</span>
                                <div>
                                    <h5>{{ __('أدخل بياناتك الأساسية') }}</h5>
                                    <ul>
                                        <li>{{ __('الاسم الكامل') }}</li>
                                        <li>{{ __('البريد الإلكتروني') }}</li>
                                        <li>{{ __('كلمة مرور قوية') }}</li>
                                    </ul>
                                    <p class="text-muted">{{ __('أو يمكنك التسجيل السريع باستخدام حساب Google') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="d-flex align-items-start">
                                <span class="step-number">3</span>
                                <div>
                                    <h5>{{ __('تأكيد البريد الإلكتروني') }}</h5>
                                    <p>{{ __('ستصلك رسالة تأكيد على بريدك الإلكتروني. اضغط على رابط التفعيل.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="d-flex align-items-start">
                                <span class="step-number">4</span>
                                <div>
                                    <h5>{{ __('تسجيل الدخول والوصول لحسابك') }}</h5>
                                    <p>{{ __('بعد التفعيل، يمكنك تسجيل الدخول والبدء في إكمال ملفك الشخصي.') }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- التحقق من الهوية (KYC) -->
                    <section id="kyc" class="guide-section">
                        <h2><i class="mdi mdi-shield-check"></i> {{ __('التحقق من الهوية (KYC)') }}</h2>
                        <p>{{ __('لضمان أمان وامتثال المنصة، يجب عليك إكمال عملية التحقق من الهوية قبل البدء في الاستثمار.') }}
                        </p>

                        <div class="alert alert-info">
                            <h5>{{ __('المستندات المطلوبة:') }}</h5>
                            <ul class="mb-0">
                                <li>{{ __('صورة من الهوية الوطنية أو جواز السفر') }}</li>
                                <li>{{ __('صورة شخصية (Selfie) لمطابقة الهوية') }}</li>
                                <li>{{ __('إثبات عنوان السكن (فاتورة مرافق حديثة)') }}</li>
                            </ul>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="mdi mdi-clock-outline display-4 text-warning"></i>
                                    <h6 class="mt-2">{{ __('قيد المراجعة') }}</h6>
                                    <small class="text-muted">{{ __('1-3 أيام عمل') }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="mdi mdi-check-circle display-4 text-success"></i>
                                    <h6 class="mt-2">{{ __('موثق') }}</h6>
                                    <small class="text-muted">{{ __('جاهز للاستثمار') }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="mdi mdi-close-circle display-4 text-danger"></i>
                                    <h6 class="mt-2">{{ __('مرفوض') }}</h6>
                                    <small class="text-muted">{{ __('يرجى المحاولة مجدداً') }}</small>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- إيداع الأموال -->
                    <section id="deposit" class="guide-section">
                        <h2><i class="mdi mdi-wallet"></i> {{ __('إيداع الأموال') }}</h2>

                        <h5>{{ __('وسائل الدفع المتاحة:') }}</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="mdi mdi-credit-card display-4 text-primary"></i>
                                        <h6 class="mt-2">{{ __('البطاقات البنكية') }}</h6>
                                        <small class="text-muted">{{ __('فوري') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="mdi mdi-bank display-4 text-success"></i>
                                        <h6 class="mt-2">{{ __('التحويل البنكي') }}</h6>
                                        <small class="text-muted">{{ __('1-3 أيام') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="mdi mdi-cash display-4 text-info"></i>
                                        <h6 class="mt-2">{{ __('المحافظ الإلكترونية') }}</h6>
                                        <small class="text-muted">{{ __('فوري') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <strong>{{ __('مدة معالجة الإيداع:') }}</strong>
                            <ul class="mb-0 mt-2">
                                <li>{{ __('البطاقات البنكية: فوري') }}</li>
                                <li>{{ __('التحويل البنكي: من 1 إلى 3 أيام عمل') }}</li>
                                <li>{{ __('المحافظ الإلكترونية: فوري إلى 24 ساعة') }}</li>
                            </ul>
                        </div>
                    </section>

                    <!-- تصفح الفرص الاستثمارية -->
                    <section id="browse" class="guide-section">
                        <h2><i class="mdi mdi-magnify"></i> {{ __('تصفح الفرص الاستثمارية') }}</h2>
                        <p>{{ __('يمكنك تصفح العروض المتاحة واستخدام الفلاتر لإيجاد الفرصة الأنسب لك:') }}</p>

                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="mdi mdi-filter"></i> {{ __('الفلاتر المتاحة:') }}</h5>
                                <ul>
                                    <li>{{ __('نوع العقار (سكني، تجاري، مكاتب)') }}</li>
                                    <li>{{ __('الموقع والمدينة') }}</li>
                                    <li>{{ __('العائد المتوقع') }}</li>
                                    <li>{{ __('مدة الاستثمار') }}</li>
                                    <li>{{ __('الحد الأدنى للاستثمار') }}</li>
                                    <li>{{ __('حالة المشروع (مكتمل، تحت الإنشاء)') }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="mdi mdi-eye"></i> {{ __('ما تراه في كل عرض:') }}</h5>
                                <ul>
                                    <li>{{ __('اسم المشروع وصورته') }}</li>
                                    <li>{{ __('نسبة العائد السنوي المتوقع') }}</li>
                                    <li>{{ __('سعر السهم الواحد') }}</li>
                                    <li>{{ __('إجمالي الأسهم المتاحة') }}</li>
                                    <li>{{ __('نسبة الاكتتاب المكتملة') }}</li>
                                    <li>{{ __('تاريخ بدء وانتهاء العرض') }}</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- تفاصيل صفحة المشروع -->
                    <section id="project-details" class="guide-section">
                        <h2><i class="mdi mdi-file-document"></i> {{ __('تفاصيل صفحة المشروع') }}</h2>
                        <p>{{ __('عند الضغط على أي عرض، ستفتح صفحة تفاصيل كاملة تتضمن:') }}</p>

                        <div class="accordion" id="projectDetailsAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse1">
                                        {{ __('1. معلومات المشروع الأساسية') }}
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse show"
                                    data-bs-parent="#projectDetailsAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>{{ __('اسم المشروع والموقع الجغرافي') }}</li>
                                            <li>{{ __('نوع العقار ومساحته') }}</li>
                                            <li>{{ __('تاريخ الإنشاء أو التسليم المتوقع') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse2">
                                        {{ __('2. العائد المتوقع') }}
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse"
                                    data-bs-parent="#projectDetailsAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>{{ __('النسبة السنوية المتوقعة (%)') }}</li>
                                            <li>{{ __('دورية التوزيع (شهرية/ربع سنوية/سنوية)') }}</li>
                                            <li>{{ __('آلية حساب العائد') }}</li>
                                        </ul>
                                        <div class="alert alert-info mb-0">
                                            <strong>{{ __('ملاحظة:') }}</strong>
                                            {{ __('العائد المعروض هو متوقع وليس مضموناً، وقد يختلف العائد الفعلي.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse3">
                                        {{ __('3. مدة الاستثمار') }}
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse"
                                    data-bs-parent="#projectDetailsAccordion">
                                    <div class="accordion-body">
                                        {{ __('المدة المتوقعة للمشروع (12 شهر، 24 شهر، 36 شهر، إلخ)') }}
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse4">
                                        {{ __('4. المخاطر') }}
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse"
                                    data-bs-parent="#projectDetailsAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>{{ __('تقييم درجة المخاطر (منخفضة/متوسطة/عالية)') }}</li>
                                            <li>{{ __('المخاطر المحتملة وكيفية التعامل معها') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse5">
                                        {{ __('5. الوثائق والمستندات') }}
                                    </button>
                                </h2>
                                <div id="collapse5" class="accordion-collapse collapse"
                                    data-bs-parent="#projectDetailsAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>{{ __('نشرة الاكتتاب (Prospectus)') }}</li>
                                            <li>{{ __('الترخيص والموافقات الحكومية') }}</li>
                                            <li>{{ __('التقييم العقاري') }}</li>
                                            <li>{{ __('العقود والاتفاقيات') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- شراء الأسهم -->
                    <section id="buying" class="guide-section">
                        <h2><i class="mdi mdi-cart"></i> {{ __('طريقة شراء الأسهم العقارية') }}</h2>

                        <div class="step-card">
                            <div class="d-flex align-items-start">
                                <span class="step-number">1</span>
                                <div>
                                    <h5>{{ __('حدد عدد الأسهم') }}</h5>
                                    <p>{{ __('أدخل عدد الأسهم التي ترغب في شرائها. سيظهر لك المبلغ الإجمالي تلقائياً.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="d-flex align-items-start">
                                <span class="step-number">2</span>
                                <div>
                                    <h5>{{ __('راجع التفاصيل') }}</h5>
                                    <ul>
                                        <li>{{ __('عدد الأسهم') }}</li>
                                        <li>{{ __('سعر السهم الواحد') }}</li>
                                        <li>{{ __('الرسوم (إن وجدت)') }}</li>
                                        <li>{{ __('المبلغ الإجمالي') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="d-flex align-items-start">
                                <span class="step-number">3</span>
                                <div>
                                    <h5>{{ __('تأكيد العملية') }}</h5>
                                    <p>{{ __('اضغط على "شراء الآن". إذا كان رصيدك كافٍ، ستكتمل العملية فوراً.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="d-flex align-items-start">
                                <span class="step-number">4</span>
                                <div>
                                    <h5>{{ __('استلام التأكيد') }}</h5>
                                    <p>{{ __('ستصلك رسالة تأكيد عبر البريد الإلكتروني، ويمكنك رؤية الأسهم في محفظتك.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- حالة الاكتتاب -->
                    <section id="subscription" class="guide-section">
                        <h2><i class="mdi mdi-chart-donut"></i> {{ __('حالة الاكتتاب') }}</h2>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card text-center border-success">
                                    <div class="card-body">
                                        <i class="mdi mdi-check-circle display-4 text-success"></i>
                                        <h5 class="mt-2">{{ __('مفتوح') }}</h5>
                                        <p class="text-muted small">{{ __('يمكنك الاكتتاب الآن') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center border-warning">
                                    <div class="card-body">
                                        <i class="mdi mdi-progress-clock display-4 text-warning"></i>
                                        <h5 class="mt-2">{{ __('قيد التمويل') }}</h5>
                                        <p class="text-muted small">{{ __('اكتمل الاكتتاب وجاري المعالجة') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center border-danger">
                                    <div class="card-body">
                                        <i class="mdi mdi-close-circle display-4 text-danger"></i>
                                        <h5 class="mt-2">{{ __('مكتمل') }}</h5>
                                        <p class="text-muted small">{{ __('انتهى الاكتتاب') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- توزيع الأرباح -->
                    <section id="profits" class="guide-section">
                        <h2><i class="mdi mdi-cash-multiple"></i> {{ __('توزيع الأرباح') }}</h2>
                        <p>{{ __('تُوزّع الأرباح بشكل دوري حسب نوع المشروع:') }}</p>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>{{ __('دورية التوزيع') }}</th>
                                        <th>{{ __('آلية الاستلام') }}</th>
                                        <th>{{ __('الإشعار') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ __('شهري') }}</td>
                                        <td>{{ __('تحويل مباشر للمحفظة') }}</td>
                                        <td>{{ __('إشعار بريد إلكتروني + SMS') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('ربع سنوي') }}</td>
                                        <td>{{ __('تحويل مباشر للمحفظة') }}</td>
                                        <td>{{ __('إشعار بريد إلكتروني + SMS') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('سنوي') }}</td>
                                        <td>{{ __('تحويل مباشر للمحفظة') }}</td>
                                        <td>{{ __('إشعار بريد إلكتروني + SMS') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info">
                            <strong><i class="mdi mdi-lightbulb"></i> {{ __('نصيحة:') }}</strong>
                            {{ __('يمكنك إعادة استثمار الأرباح في فرص جديدة لتحقيق نمو تراكمي.') }}
                        </div>
                    </section>

                    <!-- السوق الثانوي -->
                    <section id="secondary" class="guide-section">
                        <h2><i class="mdi mdi-swap-horizontal"></i> {{ __('السوق الثانوي وإعادة التداول') }}</h2>
                        <p>{{ __('بعد انتهاء فترة القفل (إن وجدت)، يمكنك بيع أسهمك في السوق الثانوي:') }}</p>

                        <h5>{{ __('خطوات البيع:') }}</h5>
                        <ol>
                            <li>{{ __('انتقل إلى محفظتك واختر الأسهم التي ترغب في بيعها') }}</li>
                            <li>{{ __('حدد السعر الذي تريد البيع به (أو اقبل سعر السوق)') }}</li>
                            <li>{{ __('أدخل عدد الأسهم') }}</li>
                            <li>{{ __('انشر عرض البيع') }}</li>
                            <li>{{ __('عند وجود مشتري، ستكتمل العملية تلقائياً') }}</li>
                        </ol>

                        <div class="alert alert-warning">
                            <strong>{{ __('ملاحظة مهمة:') }}</strong>
                            {{ __('أسعار السوق الثانوي تخضع لقوى العرض والطلب وقد تختلف عن سعر الشراء الأولي.') }}
                        </div>
                    </section>

                    <!-- الرسوم والعمولات -->
                    <section id="fees" class="guide-section">
                        <h2><i class="mdi mdi-cash-refund"></i> {{ __('الرسوم والعمولات (شفافية كاملة)') }}</h2>

                        <div class="fee-table table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('نوع العملية') }}</th>
                                        <th>{{ __('الرسوم') }}</th>
                                        <th>{{ __('ملاحظات') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ __('التسجيل') }}</td>
                                        <td class="text-success fw-bold">{{ __('مجاني') }}</td>
                                        <td>{{ __('بدون أي رسوم') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('إيداع الأموال') }}</td>
                                        <td class="text-success fw-bold">{{ __('مجاني') }}</td>
                                        <td>{{ __('قد تفرض البنوك رسوم تحويل') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('شراء الأسهم (السوق الأولي)') }}</td>
                                        <td>2%</td>
                                        <td>{{ __('من قيمة العملية') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('رسوم إدارة سنوية') }}</td>
                                        <td>1%</td>
                                        <td>{{ __('من قيمة المحفظة') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('بيع الأسهم (السوق الثانوي)') }}</td>
                                        <td>2.5%</td>
                                        <td>{{ __('من قيمة البيع') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('سحب الأرباح') }}</td>
                                        <td class="text-success fw-bold">{{ __('مجاني') }}</td>
                                        <td>{{ __('حد أدنى للسحب: 100 ريال') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- المخاطر والتنبيهات -->
                    <section id="risks" class="guide-section">
                        <h2><i class="mdi mdi-alert-octagon"></i> {{ __('المخاطر والتنبيهات القانونية') }}</h2>

                        <div class="alert alert-danger">
                            <h5><i class="mdi mdi-information"></i> {{ __('تنويه قانوني مهم') }}</h5>
                            <p>{{ __('الاستثمار في الأسهم العقارية ينطوي على مخاطر، وقد تخسر جزءاً أو كل رأس المال المستثمر.') }}
                            </p>
                        </div>

                        <h5>{{ __('المخاطر الرئيسية:') }}</h5>
                        <ul>
                            <li><strong>{{ __('مخاطر السوق:') }}</strong> {{ __('انخفاض قيمة العقار بسبب ظروف السوق') }}
                            </li>
                            <li><strong>{{ __('مخاطر السيولة:') }}</strong> {{ __('صعوبة البيع في السوق الثانوي') }}
                            </li>
                            <li><strong>{{ __('مخاطر التشغيل:') }}</strong> {{ __('انخفاض الإشغال أو الإيرادات') }}</li>
                            <li><strong>{{ __('مخاطر قانونية:') }}</strong> {{ __('تغييرات في القوانين والأنظمة') }}
                            </li>
                            <li><strong>{{ __('مخاطر البناء:') }}</strong>
                                {{ __('تأخير أو تكلفة إضافية في المشاريع تحت الإنشاء') }}</li>
                        </ul>

                        <div class="alert alert-info">
                            <strong>{{ __('نصيحة:') }}</strong>
                            {{ __('لا تستثمر أكثر مما يمكنك تحمل خسارته، ونوّع محفظتك الاستثمارية.') }}
                        </div>
                    </section>

                    <!-- حاسبة الاستثمار -->
                    <section id="calculator" class="guide-section">
                        <h2><i class="mdi mdi-calculator"></i> {{ __('حاسبة الاستثمار التفاعلية') }}</h2>

                        <div class="calculator-box">
                            <h4 class="text-white mb-4">{{ __('احسب عائدك المتوقع') }}</h4>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="text-white">{{ __('مبلغ الاستثمار (ريال)') }}</label>
                                    <input type="number" id="investment" class="form-control" value="10000"
                                        min="1000">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-white">{{ __('العائد السنوي المتوقع (%)') }}</label>
                                    <input type="number" id="return-rate" class="form-control" value="8"
                                        min="1" max="50">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-white">{{ __('مدة الاستثمار (سنوات)') }}</label>
                                    <input type="number" id="duration" class="form-control" value="3"
                                        min="1" max="30">
                                </div>
                            </div>

                            <button onclick="calculateReturn()" class="btn btn-light">{{ __('احسب') }}</button>

                            <div id="calc-result" class="mt-4 p-3 bg-white rounded" style="display:none;">
                                <h5 class="text-dark">{{ __('النتيجة:') }}</h5>
                                <p class="text-dark mb-1"><strong>{{ __('العائد السنوي:') }}</strong> <span
                                        id="annual-return"></span> {{ __('ريال') }}</p>
                                <p class="text-dark mb-1"><strong>{{ __('إجمالي العائد:') }}</strong> <span
                                        id="total-return"></span> {{ __('ريال') }}</p>
                                <p class="text-dark mb-0"><strong>{{ __('المبلغ النهائي:') }}</strong> <span
                                        id="final-amount"></span> {{ __('ريال') }}</p>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <strong>{{ __('ملاحظة:') }}</strong>
                            {{ __('هذه الحسابات تقديرية وقد يختلف العائد الفعلي حسب أداء المشروع وظروف السوق.') }}
                        </div>
                    </section>

                    <!-- دورة حياة الاستثمار -->
                    <section id="timeline" class="guide-section">
                        <h2><i class="mdi mdi-timeline"></i> {{ __('دورة حياة الاستثمار (مخطط زمني)') }}</h2>

                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h5>{{ __('1. التسجيل والتحقق') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('1-3 أيام') }}</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h5>{{ __('2. إيداع الأموال') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('فوري - 3 أيام') }}</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h5>{{ __('3. شراء الأسهم') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('فوري') }}</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h5>{{ __('4. فترة القفل (إن وجدت)') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('3-12 شهر') }}</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h5>{{ __('5. استلام العوائد') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('دوري حسب المشروع') }}</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h5>{{ __('6. البيع في السوق الثانوي (اختياري)') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('في أي وقت بعد فترة القفل') }}</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h5>{{ __('7. انتهاء المشروع وتصفية الاستثمار') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('حسب مدة المشروع') }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- أمثلة عملية -->
                    <section id="examples" class="guide-section">
                        <h2><i class="mdi mdi-lightbulb-on"></i> {{ __('أمثلة عملية (سيناريوهات استثمارية)') }}</h2>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="text-primary"><i class="mdi mdi-numeric-1-circle"></i>
                                    {{ __('سيناريو المستثمر المبتدئ') }}</h5>
                                <ul>
                                    <li>{{ __('استثمر أحمد 5,000 ريال في مشروع سكني بعائد 7% سنوياً') }}</li>
                                    <li>{{ __('حصل على 350 ريال سنوياً (29 ريال شهرياً)') }}</li>
                                    <li>{{ __('بعد سنتين، باع أسهمه بمكسب 8% = 400 ريال') }}</li>
                                    <li class="fw-bold text-success">{{ __('إجمالي الربح: 350 × 2 + 400 = 1,100 ريال') }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="text-success"><i class="mdi mdi-numeric-2-circle"></i>
                                    {{ __('سيناريو المستثمر المتوسط') }}</h5>
                                <ul>
                                    <li>{{ __('استثمرت سارة 50,000 ريال في 3 مشاريع مختلفة') }}</li>
                                    <li>{{ __('نوّعت بين مشاريع سكنية وتجارية') }}</li>
                                    <li>{{ __('حصلت على عائد سنوي متوسط 9%') }}</li>
                                    <li class="fw-bold text-success">{{ __('العائد السنوي: 4,500 ريال') }}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-info"><i class="mdi mdi-numeric-3-circle"></i>
                                    {{ __('سيناريو المستثمر المحترف') }}</h5>
                                <ul>
                                    <li>{{ __('استثمر خالد 200,000 ريال في محفظة متنوعة') }}</li>
                                    <li>{{ __('أعاد استثمار الأرباح بشكل دوري') }}</li>
                                    <li>{{ __('حقق عائد تراكمي بمعدل 12% سنوياً') }}</li>
                                    <li class="fw-bold text-success">
                                        {{ __('بعد 5 سنوات، أصبحت محفظته بقيمة 352,000 ريال') }}</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- الأمان والحماية -->
                    <section id="security" class="guide-section">
                        <h2><i class="mdi mdi-security"></i> {{ __('الأمان وحماية أموال المستثمرين') }}</h2>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h5><i class="mdi mdi-shield-check text-success"></i>
                                            {{ __('الترخيص والرقابة') }}</h5>
                                        <ul>
                                            <li>{{ __('منصة مرخصة من هيئة السوق المالية') }}</li>
                                            <li>{{ __('خاضعة لرقابة تنظيمية صارمة') }}</li>
                                            <li>{{ __('التزام كامل بمعايير الشفافية') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h5><i class="mdi mdi-lock text-primary"></i> {{ __('حماية البيانات') }}</h5>
                                        <ul>
                                            <li>{{ __('تشفير SSL 256-bit') }}</li>
                                            <li>{{ __('مصادقة ثنائية (2FA)') }}</li>
                                            <li>{{ __('خوادم آمنة ومحمية') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card border-info">
                                    <div class="card-body">
                                        <h5><i class="mdi mdi-bank text-info"></i> {{ __('فصل الحسابات المالية') }}</h5>
                                        <ul>
                                            <li>{{ __('حسابات العملاء منفصلة تماماً') }}</li>
                                            <li>{{ __('شراكة مع بنوك معتمدة') }}</li>
                                            <li>{{ __('مراجعة مالية دورية') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h5><i class="mdi mdi-eye text-warning"></i> {{ __('الشفافية الكاملة') }}</h5>
                                        <ul>
                                            <li>{{ __('إفصاح دوري عن أداء المشاريع') }}</li>
                                            <li>{{ __('تقارير مالية ربع سنوية') }}</li>
                                            <li>{{ __('إشعارات فورية بأي تغيير') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- الأسئلة الشائعة -->
                    <section id="faq" class="guide-section">
                        <h2><i class="mdi mdi-frequently-asked-questions"></i> {{ __('الأسئلة الشائعة (FAQ)') }}</h2>

                        <div id="faqAccordion">
                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span>{{ __('ما هو الحد الأدنى للاستثمار؟') }}</span>
                                    <i class="mdi mdi-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    {{ __('الحد الأدنى يختلف حسب المشروع، لكنه عادة يبدأ من 1,000 ريال سعودي، مما يجعل الاستثمار متاحاً للجميع.') }}
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span>{{ __('متى أستلم العوائد؟') }}</span>
                                    <i class="mdi mdi-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    {{ __('تُوزّع العوائد بشكل دوري (شهري، ربع سنوي، أو سنوي) حسب نوع المشروع، وتحوّل مباشرة إلى محفظتك.') }}
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span>{{ __('هل يمكنني بيع أسهمي في أي وقت؟') }}</span>
                                    <i class="mdi mdi-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    {{ __('نعم، بعد انتهاء فترة القفل (إن وجدت)، يمكنك بيع أسهمك في السوق الثانوي لمستثمرين آخرين.') }}
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span>{{ __('ما الفرق بين العائد المتوقع والعائد الفعلي؟') }}</span>
                                    <i class="mdi mdi-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    {{ __('العائد المتوقع هو تقدير يعتمد على دراسات الجدوى، بينما العائد الفعلي يعتمد على الأداء الحقيقي للمشروع وقد يكون أعلى أو أقل.') }}
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span>{{ __('هل المنصة مرخصة وخاضعة للرقابة؟') }}</span>
                                    <i class="mdi mdi-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    {{ __('نعم، منصة سهمي مرخصة من الجهات التنظيمية المختصة وخاضعة لرقابة صارمة لضمان حماية المستثمرين.') }}
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span>{{ __('كيف أتحقق من جودة المشروع؟') }}</span>
                                    <i class="mdi mdi-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    {{ __('يمكنك الاطلاع على جميع الوثائق والتقارير المالية والتقييم العقاري والترخيص في صفحة تفاصيل المشروع.') }}
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span>{{ __('هل يوجد رسوم خفية؟') }}</span>
                                    <i class="mdi mdi-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    {{ __('لا، جميع الرسوم معلنة بشفافية كاملة في جدول الرسوم أعلاه. لا توجد أي رسوم مخفية.') }}
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span>{{ __('ماذا يحدث إذا فشل المشروع؟') }}</span>
                                    <i class="mdi mdi-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    {{ __('في حالة فشل المشروع (نادر جداً)، هناك آليات حماية قانونية، وقد يتم تصفية الأصول وتوزيع العوائد على المستثمرين حسب حصصهم.') }}
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- دعوة لاتخاذ إجراء -->
                    <section id="start" class="guide-section text-center bg-light rounded p-5">
                        <h2 class="mb-4">{{ __('جاهز للبدء؟') }}</h2>
                        <p class="lead mb-4">{{ __('انضم إلى آلاف المستثمرين الذين يحققون عوائد من الأسهم العقارية') }}
                        </p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ route('marketplace.register') }}" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-account-plus"></i> {{ __('ابدأ الاستثمار الآن') }}
                            </a>
                            <a href="{{ route('marketplace.offers.index') }}" class="btn btn-outline-primary btn-lg">
                                <i class="mdi mdi-eye"></i> {{ __('تصفح الفرص المتاحة') }}
                            </a>
                        </div>
                    </section>

                    <!-- روابط السياسات -->
                    <section class="guide-section">
                        <h5>{{ __('مستندات قانونية') }}</h5>
                        <ul class="list-unstyled">
                            <li><a href="#">{{ __('الشروط والأحكام') }}</a></li>
                            <li><a href="#">{{ __('سياسة الخصوصية') }}</a></li>
                            <li><a href="#">{{ __('اتفاقية المستخدم') }}</a></li>
                            <li><a href="#">{{ __('سياسة إدارة المخاطر') }}</a></li>
                            <li><a href="#">{{ __('سياسة مكافحة غسل الأموال') }}</a></li>
                        </ul>
                    </section>

                    </div> <!-- نهاية guide-content -->
                </div> <!-- نهاية col-lg-9 -->
            </div> <!-- نهاية row -->
        </div> <!-- نهاية container -->

    </div> <!-- نهاية guide-page-wrapper -->
    <!-- نهاية guide-page-wrapper -->

    <script>
        // حاسبة الاستثمار
        function calculateReturn() {
            const investment = parseFloat(document.getElementById('investment').value);
            const returnRate = parseFloat(document.getElementById('return-rate').value) / 100;
            const duration = parseInt(document.getElementById('duration').value);

            const annualReturn = investment * returnRate;
            const totalReturn = annualReturn * duration;
            const finalAmount = investment + totalReturn;

            document.getElementById('annual-return').textContent = annualReturn.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,
                ",");
            document.getElementById('total-return').textContent = totalReturn.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,
                ",");
            document.getElementById('final-amount').textContent = finalAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,
                ",");

            document.getElementById('calc-result').style.display = 'block';
        }

        // FAQ Toggle
        function toggleFaq(element) {
            const faqItem = element.closest('.faq-item');
            const allItems = document.querySelectorAll('.faq-item');

            allItems.forEach(item => {
                if (item !== faqItem && item.classList.contains('active')) {
                    item.classList.remove('active');
                }
            });

            faqItem.classList.toggle('active');
        }

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // تحديث active class
                    document.querySelectorAll('.guide-sidebar a').forEach(link => link.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
        
        // تفعيل الرابط النشط حسب الـ scroll
        window.addEventListener('scroll', function() {
            let current = '';
            const sections = document.querySelectorAll('.guide-section[id]');
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });
            
            document.querySelectorAll('.guide-sidebar a').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Google Analytics Events Tracking (مثال)
        function trackEvent(category, action, label) {
            if (typeof gtag !== 'undefined') {
                gtag('event', action, {
                    'event_category': category,
                    'event_label': label
                });
            }
        }

        // تتبع النقرات على الأزرار
        document.querySelectorAll('a.btn').forEach(btn => {
            btn.addEventListener('click', function() {
                trackEvent('Button', 'Click', this.textContent.trim());
            });
        });
    </script>
@endsection
