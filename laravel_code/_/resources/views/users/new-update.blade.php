@extends('layouts.app')
@section('body_class', 'new-post-page')
@section('css')
    <style>
        .new-post-header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            min-height: 36px;
        }

        .new-post-back {
            position: absolute;
            left: 0;
            top: 0;
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: transparent;
            text-decoration: none !important;
            flex: 0 0 auto;
        }

        .new-post-title {
            margin: 0;
            font-weight: 700;
            font-size: 24px;
            line-height: 1.2;
            text-align: center;
        }
        [data-bs-theme="dark"].new-post-title, .new-post-back {
            color: #fff;
        }
        [data-bs-theme="light"].new-post-title, .new-post-back {
            color: #344054;
        }
        [data-bs-theme="light"] #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input {
            background:#ffffff !important;
        }
        @media (max-width: 768px) {
            .new-post-header {
                margin-bottom: 10px;
            }

            .new-post-title {
                font-size: 24px;
            }
        }

        .fileuploader-items {
            white-space: unset !important;
        }

        #formUpdateCreate .fileuploader {
            width: 100%;
            margin: 0 0 24px !important;
            padding: 0 !important;
            background: transparent !important;
            border-radius: 0 !important;
            min-height: 0 !important;
        }

        #formUpdateCreate .rounded-large {
            position: relative;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input {
            min-height: 674px !important;
            border: 0 !important;
            border-radius: 20px !important;
            background: #333438;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-inner {
            display: flex !important;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 24px;
            width: 100%;
            max-width: 320px;
            padding: 0;
            margin: 0 auto;
            text-align: center;
            background: transparent !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input p {
            display: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-icon-main {
            width: 80px;
            height: 80px;
            border-radius: 999px;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            background: #191919 !important;
            font-size: 0 !important;
            position: relative;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-icon-main::before {
            content: "" !important;
            width: 32px !important;
            height: 32px !important;
            display: inline-block;
            background: none !important;
            border: 2px solid #fff;
            border-radius: 999px;
            box-sizing: border-box;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-icon-main::after {
            content: "!";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -53%);
            color: #fff;
            font-size: 19px;
            font-weight: 500;
            line-height: 1;
            font-family: Poppins, sans-serif;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3 {
            width: 309px;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 0 !important;
            line-height: 0 !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3 span {
            display: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3::after {
            content: "Drag and drop files or click\Ato upload";
            white-space: pre-line;
            display: block;
            font-family: Poppins, sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 28px;
            letter-spacing: -0.439453px;
            margin: 0;
        }
        [data-bs-theme="light"] #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3::after {
            color: black;
        }
        [data-bs-theme="dark"] #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3::after {
            color: #fff;
        }
        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-button {
            /* width: 156px !important; */
            height: 40px !important;
            border: 0 !important;
            border-radius: 8px !important;
            background: #191919 !important;
            color: #fff !important;
            box-shadow: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px;
            padding: 12px 8px !important;
            margin: 0 !important;
            position: static !important;
            transform: none !important;
            overflow: hidden;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-button span {
            display: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-button::before {
            content: "";
            width: 24px;
            height: 24px;
            flex: 0 0 24px;
            display: inline-block;
            background: url("data:image/svg+xml;utf8,<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M4 10V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V10' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/><path d='M12 4V14' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/><path d='M9 7L12 4L15 7' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>") no-repeat center / contain;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-button::after {
            content: "Choose Files";
            color: #fff;
            font-family: Poppins, sans-serif;
            font-weight: 600;
            font-size: 18px;
            line-height: 27px;
        }

        @media (max-width: 768px) {
            #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input {
                min-height: 294px !important;
                border-radius: 16px !important;
            }

            #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3::after {
                font-size: 16px;
                line-height: 24px;
            }
        }

        #formUpdateCreate .fileuploader-items {
            display: none !important;
        }

        #formUpdateCreate.step-upload .fileuploader-items {
            display: block !important;
            margin-top: 14px;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item {
            position: relative;
            width: 100%;
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: #23262f;
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
            cursor: pointer;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item.preview-active {
            border-color: #e53b54;
            box-shadow: 0 0 0 2px rgba(229, 59, 84, 0.28);
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.26);
            box-shadow: 0 14px 26px rgba(0, 0, 0, 0.24);
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .columns {
            display: block;
            min-height: 0;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .column-thumbnail {
            width: 100%;
            height: 130px;
            position: relative;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item-image {
            width: 100%;
            height: 100%;
            border-radius: 0;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item-image img,
        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item-image canvas {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .column-title,
        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .content-holder {
            padding: 10px 10px 11px !important;
            color: #fff;
            background: linear-gradient(180deg, rgba(46, 50, 62, 0.98) 0%, rgba(31, 34, 43, 0.98) 100%);
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .column-title div,
        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .content-holder h5 {
            margin: 0;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .column-title span,
        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .content-holder span {
            display: block;
            margin-top: 4px;
            color: #9da5b8;
            font-size: 11px;
            line-height: 1.2;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .column-actions,
        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .actions-holder {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 3;
            margin: 0 !important;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .fileuploader-action-remove {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(9, 10, 12, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #fff;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .fileuploader-action-remove:hover {
            background: rgba(229, 59, 84, 0.92);
            border-color: rgba(229, 59, 84, 0.92);
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .progress-bar2,
        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .progress-holder {
            margin: 0 10px 10px;
        }

        @media (max-width: 575.98px) {
            #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list .fileuploader-item .column-thumbnail {
                height: 108px;
            }
        }

        #formUpdateCreate.step-preview .fileuploader,
        #formUpdateCreate.step-details .fileuploader {
            display: none !important;
        }

        #formUpdateCreate.step-upload .fileuploader-theme-dragdrop .fileuploader-items-list {
            display: none !important;
        }

        #formUpdateCreate.step-upload #postPreviewStep,
        #formUpdateCreate.step-upload #postDetailsStep {
            display: none !important;
        }

        #formUpdateCreate .post-preview-continue-wrap {
            display: none;
            margin-top: 16px;
        }

        #formUpdateCreate.step-preview .post-preview-continue-wrap {
            display: block;
        }

        .post-preview-step {
            display: none;
            background: #d7d7d9;
            border-radius: 20px;
            padding: 0;
            min-height: 674px;
            position: relative;
            overflow: hidden;
        }

        .post-preview-step.active {
            display: block;
        }

        .post-preview-frame {
            margin-top: 0;
            border-radius: 20px;
            background: #ececf0;
            position: relative;
            overflow: hidden;
            min-height: 674px;
        }

        .post-details-step {
            display: none;
        }

        .post-details-step.active {
            display: block;
        }

        .post-details-back-wrap {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .post-details-back {
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 10px;
            background: rgba(25, 25, 25, 0.5);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .post-details-add-more {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 2px dashed rgba(229, 59, 84, 0.5);
            background: transparent;
            color: #e53b54;
            display: none;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            position: relative;
        }

        .post-details-add-more::before,
        .post-details-add-more::after {
            content: "";
            position: absolute;
            background: currentColor;
            border-radius: 999px;
        }

        .post-details-add-more::before {
            width: 16px;
            height: 2px;
        }

        .post-details-add-more::after {
            width: 2px;
            height: 16px;
        }

        #formUpdateCreate.can-add-more-details .post-details-add-more {
            display: inline-flex;
        }

        .post-preview-media {
            border-radius: 20px;
            overflow: hidden;
            height: 674px;
            background: #e8e8eb;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .post-preview-delete {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 6;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(8, 10, 14, 0.76);
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #fff;
            cursor: pointer;
            padding: 0;
        }

        .post-preview-delete:hover {
            background: rgba(229, 59, 84, 0.94);
            border-color: rgba(229, 59, 84, 0.94);
            color: #fff;
        }

        .post-preview-media img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
            transform-origin: center center;
            cursor: grab;
        }

        .post-preview-media img.is-empty {
            display: none;
        }

        .post-preview-media img.is-dragging {
            cursor: grabbing;
        }

        .post-preview-thumbs {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 8px;
            overflow-x: auto;
            padding: 2px;
            margin: 10px 14px 0;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .post-preview-thumbs::-webkit-scrollbar {
            display: none;
        }

        .post-preview-thumb {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            border: 2px solid transparent;
            overflow: hidden;
            background: #f3f3f5;
            padding: 0;
            flex: 0 0 auto;
        }

        .post-preview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .post-preview-thumb.active {
            border-color: #e53b54;
        }

        .post-preview-thumb--add {
            border: 2px dashed rgba(255, 255, 255, 0.22);
            background: transparent;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .post-preview-thumb--add::before,
        .post-preview-thumb--add::after {
            content: "";
            position: absolute;
            background: currentColor;
            border-radius: 999px;
        }

        .post-preview-thumb--add::before {
            width: 18px;
            height: 2px;
        }

        .post-preview-thumb--add::after {
            width: 2px;
            height: 18px;
        }

        html[data-bs-theme="dark"] .post-preview-step {
            background: #333438 !important;
        }

        html[data-bs-theme="dark"] .post-preview-frame {
            background: #333438 !important;
        }

        html[data-bs-theme="dark"] .post-preview-media {
            background: #333438 !important;
        }

        html[data-bs-theme="dark"] .post-preview-thumb {
            background: #3b3d44;
        }

        html[data-bs-theme="dark"] .post-preview-thumb--add {
            color: #fff;
        }

        html[data-bs-theme="light"] .post-preview-step {
            background: #ffffff;
        }

        html[data-bs-theme="light"] .post-preview-frame {
            background: #ffffff;
        }

        html[data-bs-theme="light"] .post-preview-media {
            background: #ffffff;
        }

        html[data-bs-theme="light"] .post-preview-thumb--add {
            color: #111827;
            border-color: rgba(17, 24, 39, 0.18);
            background: #f4f4f5;
        }

        .post-preview-controls {
            position: absolute;
            left: 30px;
            right: 30px;
            bottom: 20px;
            display: flex;
            flex-direction: column;
            z-index: 4;
        }

        .upload-processing-overlay {
            display: none;
            position: absolute;
            inset: 0;
            border-radius: 20px;
            background: rgba(25, 25, 25, 0.86);
            z-index: 5;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #fff;
            gap: 10px;
        }

        .upload-processing-overlay.active {
            display: flex;
        }

        .upload-processing-spinner {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            border-top-color: #fff;
            animation: post-upload-spin 0.9s linear infinite;
        }

        .upload-processing-text {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        @keyframes post-upload-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .post-preview-range {
            width: 100%;
            margin-bottom: 16px;
            -webkit-appearance: none;
            appearance: none;
            height: 14px;
            border-radius: 999px;
            background: linear-gradient(to right, #030213 var(--zoom-value, 100%), #d9d9df var(--zoom-value, 100%));
            outline: none;
        }

        .post-preview-range::-webkit-slider-runnable-track {
            height: 14px;
            border-radius: 999px;
            background: transparent;
        }

        .post-preview-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 28px;
            height: 28px;
            margin-top: -7px;
            border-radius: 50%;
            background: #fff;
            border: 6px solid #030213;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
            cursor: pointer;
        }

        .post-preview-range::-moz-range-track {
            height: 14px;
            border-radius: 999px;
            background: #d9d9df;
        }

        .post-preview-range::-moz-range-progress {
            height: 14px;
            border-radius: 999px;
            background: #030213;
        }

        .post-preview-range::-moz-range-thumb {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #fff;
            border: 6px solid #030213;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
            cursor: pointer;
        }

        .post-preview-continue {
            width: 100%;
            border: 0;
            border-radius: 8px;
            background: #e53b54;
            color: #fff;
            font-weight: 700;
            padding: 12px 14px;
            margin-top: 0;
        }

        @media (max-width: 768px) {
            .post-preview-controls {
                left: 16px;
                right: 16px;
                bottom: 16px;
            }

            .post-preview-frame,
            .post-preview-media {
                min-height: 500px;
                height: 500px;
            }

            .post-preview-thumbs {
                margin: 8px 10px 0;
            }

            #formUpdateCreate .post-preview-continue-wrap {
                margin-top: 12px;
            }
        }

        /* .advanced-settings {
            color: #fff;
        } */

        .setting-label {
            display: block;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .visibility-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .visibility-btn {
            background: #2a2a2a;
            border: none;
            color: #ddd;
            padding: 8px 14px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.2s;
            font-size: 14px;
        }

        .visibility-btn:hover {
            background: #3a3a3a;
        }

        .visibility-btn.active {
            background: #444;
            color: #fff;
        }

        .visibility-btn.is-disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .setting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 5px;
            min-width: 0;
        }

        .setting-row > div {
            flex: 1 1 auto;
            min-width: 0;
        }

        /* Toggle Switch */
        .switch_update {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }

        .switch_update input {
            display: none;
        }

        .slider_update {
            position: absolute;
            cursor: pointer;
            background-color: #555;
            border-radius: 24px;
            inset: 0;
            transition: .3s;
        }

        .slider_update:before {
            content: "";
            position: absolute;
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: .3s;
        }

        .switch_update input:checked + .slider_update {
            background-color: #ff4d6d;
        }

        .switch_update input:checked + .slider_update:before {
            transform: translateX(22px);
        }

        .setting-help {
            display: block;
            margin-top: 8px;
            color: #9ca3af;
            font-size: 12px;
            line-height: 1.4;
        }

        .description-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        @media (max-width: 480px) {
            .post-preview-step{
                min-height:300px !important;
            }
            .post-preview-media{
                height:294px !important;
            }

            .setting-row {
                align-items: flex-start;
                gap: 10px;
                padding-inline: 0;
            }

            .switch_update {
                flex: 0 0 auto;
                margin-top: 2px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="section section-sm">
        {{-- for mobile header --}}
        @include('includes.header-mobile')
        <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
            <div class="row">
                <div class="col-lg-3 col-md-4 side_bar_box_shadow">
                    @include('includes.menu-sidebar-home')
                </div>
                <div class="col-lg-6 col-md-8 p-0">
                        @include('includes.alert-payment-disabled')
                        <div class="progress-wrapper px-3 px-lg-0 display-none mb-3" id="progress">
                            <div class="progress-info">
                                <div class="progress-percentage">
                                    <span class="percent">0%</span>
                                </div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>

                    <div class="new-post-header">
                        <a href="javascript:void(0);" id="newPostHeaderBack" class="new-post-back" title="{{ __('general.go_back') }}">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h4 class="new-post-title">{{ __('general.new_post') }}</h4>
                    </div>

                    <div class="pb-3 px-3">
                        <form method="POST" action="{{ url('update/create') }}" enctype="multipart/form-data"
                            id="formUpdateCreate">
                            @csrf
                            <div class="post-composer-dark">
                                <div class="blocked display-none"></div>
                                <div class="pb-0">
                                    <div class="media">
                                    </div><!-- media -->
                                    <input class="custom-control-input d-none" id="customCheckLocked" type="checkbox"
                                        {{ auth()->user()->post_locked == 'yes' ? 'checked' : '' }} name="locked"
                                        value="yes">

                                    <!-- Alert -->

                                    <div class="alert alert-danger my-3 display-none" id="errorUdpate">

                                        <ul class="list-unstyled m-0" id="showErrorsUdpate"></ul>

                                    </div><!-- Alert -->

                                </div>

                                <div class="rounded-large">

                                    <div class="justify-content-between align-items-center">
                                        
                                        <div class="w-100 mb-2">

                                            <small id="previewImage"></small>

                                            <a href="javascript:void(0)" id="removePhoto"
                                                class="text-danger p-1 small display-none btn-tooltip-form"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{ __('general.delete') }}"><i class="fa fa-times-circle"></i></a>

                                        </div>

                                        <div class="w-100 mb-2">

                                            <small id="previewEpub"></small>

                                            <a href="javascript:void(0)" id="removeEpub"
                                                class="text-danger p-1 small display-none btn-tooltip-form"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{ __('general.delete') }}"><i class="fa fa-times-circle"></i></a>

                                        </div>
                                        
                                        @php
                                            $postUploadAccept = $settings->video_encoding == 'off'
                                                ? 'image/*,video/mp4,audio/mp3'
                                                : 'image/*,video/mp4,video/quicktime,video/3gpp,video/mpeg,video/x-matroska,video/x-ms-wmv,video/vnd.avi,video/avi,video/x-flv,audio/mp3';
                                        @endphp
                                        <input type="file" name="photo[]" id="filePhoto"
                                            accept="{{ $postUploadAccept }}" multiple class="visibility-hidden filepond">
                                        <div id="uploadProcessingOverlay" class="upload-processing-overlay">
                                            <span class="upload-processing-spinner"></span>
                                            <span class="upload-processing-text">{{ __('users.uploading') }} <strong id="uploadProcessingPercent">0%</strong></span>
                                        </div>

                                            <div id="postPreviewStep" class="post-preview-step">
                                            <div class="post-preview-frame">
                                                <div class="post-preview-media">
                                                    <button type="button" id="postPreviewDelete" class="post-preview-delete"
                                                        title="{{ __('general.delete') }}" aria-label="{{ __('general.delete') }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    <img id="postPreviewImage" src="" alt="{{ __('general.preview') }}">
                                                </div>
                                                <div class="post-preview-controls">
                                                    <input id="postPreviewZoom" class="post-preview-range" type="range" min="100" max="300" step="1" value="100">
                                                </div>
                                            </div>
                                            <div id="postPreviewThumbs" class="post-preview-thumbs"></div>
                                        </div>
                                        <div class="post-preview-continue-wrap">
                                            <button type="button" id="postPreviewContinue" class="post-preview-continue">{{ __('general.continue_action') }}</button>
                                        </div>
                                        
                                            
                                        {{-- for hide on first load start  --}}
                                        <div id="postDetailsStep" class="post-details-step">
                                            <div class="post-details-back-wrap">
                                                <button type="button" id="postDetailsBack" class="post-details-back">
                                                    <i class="fas fa-arrow-left"></i>
                                                </button>
                                                <button type="button" id="postDetailsAddMore" class="post-details-add-more" aria-label="{{ __('general.upload_media') }}" title="{{ __('general.upload_media') }}"></button>
                                            </div>
                                            <div class="form-group" id="titlePost">
                                                <label>{{__('general.title')}}</label>
                                                <div class="input-group mb-2">
                                                    <input class="form-control" autocomplete="off" name="title"
                                                    maxlength="100" placeholder="{{ __('admin.title') }}" type="text">
                                                </div>
                                                <small class="form-text text-muted mb-4">
                                                    {{ __('general.title_post_info', ['numbers' => 100]) }}
                                                </small>

                                            </div><!-- End form-group -->
                                            <div class="description-row">
                                                <label class="mb-0">{{__('general.description')}}</label>
                                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    class="btn btn-post p-bottom-8 btn-tooltip-form e-none @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill"
                                                    title="Emoji">
                                                    <i class="bi-emoji-smile f-size-20 align-bottom"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar">
                                                    @include('includes.emojis')
                                                </div>
                                            </div>
                                            <textarea name="description" id="updateDescription" data-post-length="{{ $settings->update_length }}" rows="5"
                                                cols="40" placeholder="{{ __('general.write_something') }}"
                                                class="form-control textareaAutoSize updateDescription emojiArea"></textarea>
                                            <div class="form-group display-none mt-3" id="price">
                                                <label>{{ __('general.price') }}</label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ $settings->currency_symbol }}</span>
                                                    </div>

                                                    <input class="form-control isNumber" autocomplete="off" name="price"
                                                        placeholder="{{ __('general.price') }}" type="text">
                                                </div>
                                            </div><!-- End form-group -->
                                            <input type="hidden" name="scheduled_date" id="inputScheduled" value="">
                                            <input type="hidden" id="visibilityMode" value="everyone">
                                            <div class="w-100 mb-3 display-none" id="dateScheduleContainer">
                                                <small class="font-weight-bold">
                                                    <i class="bi-calendar-event mr-1"></i> {{ __('general.date_schedule') }} <span id="dateSchedule"></span>
                                                </small>
                                                <a href="javascript:void(0)" id="deleteSchedule" class="text-danger p-1 px-2 btn-tooltip-form"
                                                    data-toggle="tooltip" data-placement="top" title="{{ __('general.delete') }}"><i class="fa fa-times-circle"></i></a>
                                            </div>

                                            <hr class="my-4">
                                            <div class="advanced-settings">

                                                <h4 class="mb-3 fw-bold">{{__('general.advanced_settings')}}</h4>

                                                <!-- Who can see this post -->
                                                <div class="mb-4">
                                                    <label class="setting-label">{{ __('general.who_can_see_this_post') }}</label>

                                                    <div class="visibility-options">
                                                        <button type="button" class="visibility-btn active" data-visibility="everyone">
                                                            {{ __('general.everyone') }}
                                                        </button>
                                                        <button type="button" class="visibility-btn" data-visibility="subscribers">
                                                            {{ __('general.subscribers_only') }}
                                                        </button>
                                                        <button type="button" class="visibility-btn" data-visibility="premium">
                                                            {{ __('general.premium_post') }}
                                                        </button>
                                                    </div>
                                                    
                                                </div>

                                                <!-- Hide likes -->
                                                <div class="setting-row">
                                                    <div>
                                                        <h6>{{ __('general.hide_like_counts_post') }}</h6>
                                                        <small class="text-muted">
                                                            {{ __('general.hide_like_counts_post_help') }}
                                                        </small>
                                                    </div>
                                                    <label class="switch_update">
                                                        <input type="checkbox" id="hideLikesCountToggle" name="hide_likes_count" value="1">
                                                        <span class="slider_update"></span>
                                                    </label>
                                                </div>

                                                <!-- Turn off commenting -->
                                                <div class="setting-row">
                                                    <div>
                                                        <h6>{{ __('general.turn_off_commenting') }}</h6>
                                                        <small class="text-muted">
                                                            {{ __('general.turn_off_commenting_help') }}
                                                        </small>
                                                    </div>
                                                    <label class="switch_update">
                                                        <input type="checkbox" id="turnOffCommentsToggle" name="turn_off_comments" value="1">
                                                        <span class="slider_update"></span>
                                                    </label>
                                                </div>

                                                <!-- Schedule -->
                                                <div class="setting-row">
                                                    <h6>{{__('general.schedule')}}</h6>
                                                    <label class="switch_update">
                                                        <input type="checkbox" id="advScheduleToggle" @if (!$settings->allow_scheduled_posts) disabled @endif>
                                                        <span class="slider_update"></span>
                                                    </label>
                                                </div>
                                                @if (!$settings->allow_scheduled_posts)
                                                    <small class="setting-help">{{ __('general.scheduling_disabled_by_admin') }}</small>
                                                @endif

                                                <!-- Price -->
                                                <div class="setting-row">
                                                    <h6>{{__('general.price')}}</h6>
                                                    <strong id="advancedPriceValue">{{ $settings->currency_symbol }}0</strong>
                                                </div>

                                            </div>
                                        {{-- for hide on first load end  --}}
                                        
                                        @php
                                            $creatorLive = Helper::isCreatorLive(
                                                $getCurrentLiveCreators,
                                                auth()->user()->id,
                                            );
                                        @endphp
                                        {{-- added new div start --}}
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="action-left">
                                                <div class="action_avatar">
                                                    <span class="rounded-circle position-relative">
                                                        <a
                                                            href="{{ $creatorLive ? url('live', auth()->user()->username) : url('profile', auth()->user()->username) }}">
                                                            <img src="{{ Helper::getFile(config('path.avatar') . auth()->user()->avatar) }}"
                                                                alt="{{ auth()->user()->hide_name == 'yes' ? auth()->user()->username : auth()->user()->name }}"
                                                                class="rounded-circle avatarUser" width="60"
                                                                height="60">
                                                        </a>
                                                    </span>
                                                </div>
                                                <div class="action_user_info">
                                                    <div class="action_user_heading">
                                                        <strong>
                                                            <a href="{{ url('profile', auth()->user()->username) }}">
                                                                {{ auth()->user()->hide_name == 'yes' ? auth()->user()->username : auth()->user()->name }}
                                                            </a>
                                                        </strong>

                                                        @if (auth()->check() && $creatorLive)
                                                            <span class="live-span live-span-inline">{{ __('general.live') }}</span>
                                                        @endif

                                                        @if (auth()->user()->verified_id == 'yes')
                                                            <small class="verified"
                                                                title="{{ __('general.verified_account') }}"
                                                                data-toggle="tooltip" data-placement="top">
                                                                <i class="bi bi-patch-check-fill"></i>
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <span>
                                                        <small class="text-muted font-14 mt-2">{{ '@' . auth()->user()->username }}</small>
                                                    </span>
                                                </div>
                                            </div>
                                            <div>   
                                            </div>
                                        </div>
                                        {{-- end here --}}
                                        <div class="d-inline-block mt-3 position-relative w-100-mobile">

                                            <span class="d-inline-block position-relative rounded-pill w-100-mobile">

                                                <span class="btn-blocked display-none"></span>

                                                <button type="button" 
                                                    class="btn btn-sm btn-primary rounded-large float-right e-none w-100-mobile"
                                                    data-empty="{{ __('general.empty_post') }}"
                                                    data-error="{{ __('general.error') }}"
                                                    data-msg-error="{{ __('general.error_internet_disconnected') }}"
                                                    id="btnCreateUpdate">

                                                    <i></i> <span
                                                        id="textPostPublish">{{ __('general.confirm_publish') }}</span>

                                                </button>

                                            </span>

                                        </div>

                                        </div>

                                    </div>

                                </div><!-- card footer -->

                            </div><!-- card -->

                        </form>

                        <!-- Post Pending -->

                        <div class="alert alert-primary display-none card-border-0" role="alert" id="alertPostPending">

                            <button type="button" class="close mt-1" id="btnAlertPostPending">

                                <span aria-hidden="true">

                                    <i class="bi bi-x-lg"></i>

                                </span>

                            </button>

                            <i class="bi-info-circle mr-1"></i> {{ __('general.alert_post_pending_review') }}

                            <a href="{{ url('my/posts') }}" class="link-border text-white">{{ __('general.my_posts') }}</a>

                        </div>

                        <!-- Post Schedule -->

                        <div class="alert alert-primary display-none card-border-0" role="alert" id="alertPostSchedule">

                            <button type="button" class="close mt-1" id="btnAlertPostSchedule">

                                <span aria-hidden="true">

                                    <i class="bi bi-x-lg"></i>

                                </span>

                            </button>



                            <i class="bi-info-circle mr-1"></i> {{ __('general.alert_post_schedule') }}

                            <a href="{{ url('my/posts') }}" class="link-border text-white">{{ __('general.my_posts') }}</a>

                        </div>
                    </div> 
                    
                    <div class="p-3 d-lg-none">
                        @include('includes.explore_creators')
                    </div>
                </div><!-- end col-md-6 -->

                <div class="col-lg-3 col-md-4 mb-4 d-lg-block d-none">
                    @if ($users->count() == 0)
                        <div class="panel panel-default panel-transparent mb-4 d-lg-block d-none">
                            <div class="panel-body">
                                <div class="media none-overflow">
                                    <div class="d-flex my-2 align-items-center">
                                        <img class="rounded-circle mr-2"
                                            src="{{ Helper::getFile(config('path.avatar') . auth()->user()->avatar) }}"
                                            width="60" height="60">

                                        <div class="d-block">
                                            <strong>{{ auth()->user()->name }}</strong>


                                            <div class="d-block">
                                                <small class="media-heading text-muted btn-block margin-zero">
                                                    <a href="{{ url('settings/page') }}">
                                                        {{ auth()->user()->verified_id == 'yes' ? trans('general.edit_my_page') : trans('users.edit_profile') }}
                                                        <small class="pl-1"><i
                                                                class="fa fa-long-arrow-alt-right"></i></small>
                                                    </a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-lg-block" id="">

                        @if ($users->count() != 0)
                            @include('includes.explore_creators')
                        @endif
                    </div>
                </div>
            </div><!-- row -->
        </div><!-- container -->
    </section>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.fileuploader').addClass('d-block');
            var publishMode = @json(request('publish'));
            var textOnlyMode = publishMode === 'text';

            var $uploadStep = $('#formUpdateCreate .fileuploader');
            var $previewStep = $('#postPreviewStep');
            var $detailsStep = $('#postDetailsStep');
            var $previewImage = $('#postPreviewImage');
            var $previewThumbs = $('#postPreviewThumbs');
            var $zoom = $('#postPreviewZoom');
            var $form = $('#formUpdateCreate');
            var $visibilityButtons = $('.visibility-btn[data-visibility]');
            var $visibilityMode = $('#visibilityMode');
            var $priceInput = $('input[name="price"]');
            var $advancedPriceValue = $('#advancedPriceValue');
            var $scheduleToggle = $('#advScheduleToggle');
            var $uploadOverlay = $('#uploadProcessingOverlay');
            var $uploadOverlayPercent = $('#uploadProcessingPercent');
            var previewUrl = null;
            var hasUploadedVideo = false;
            var videoExtensions = ['mp4', 'mov', '3gp', 'mpeg', 'mpg', 'mkv', 'wmv', 'avi', 'flv', 'webm', 'm4v'];
            var zoomMin = 100;
            var zoomMax = 300;
            var currencySymbol = @json($settings->currency_symbol);
            var previewPan = { x: 0, y: 0 };
            var isDraggingPreview = false;
            var dragStart = { x: 0, y: 0 };
            var panStart = { x: 0, y: 0 };
            var currentScale = zoomMin / 100;
            var cropRequests = {};
            var uploadLimit = parseInt(window.maximum_files_post || 0, 10) || 0;
            var uploadMediaLabel = @json(__('general.upload_media'));
            function getDefaultPreviewState() {
                return {
                    scalePercent: zoomMin,
                    panX: 0,
                    panY: 0
                };
            }

            function getActivePreviewItem() {
                return getActiveUploadItem();
            }

            function getPreviewStateForItem($item) {
                var defaults = getDefaultPreviewState();
                if (!$item || !$item.length) {
                    return defaults;
                }

                var savedScale = parseFloat($item.attr('data-preview-scale') || '');
                var savedPanX = parseFloat($item.attr('data-preview-pan-x') || '');
                var savedPanY = parseFloat($item.attr('data-preview-pan-y') || '');

                return {
                    scalePercent: isNaN(savedScale) ? defaults.scalePercent : savedScale,
                    panX: isNaN(savedPanX) ? defaults.panX : savedPanX,
                    panY: isNaN(savedPanY) ? defaults.panY : savedPanY
                };
            }

            function savePreviewStateForItem($item) {
                if (!$item || !$item.length) {
                    return;
                }

                $item.attr('data-preview-scale', Math.round(getPreviewScale() * 100));
                $item.attr('data-preview-pan-x', Math.round(previewPan.x));
                $item.attr('data-preview-pan-y', Math.round(previewPan.y));
            }

            function saveActivePreviewState() {
                var $item = getActivePreviewItem();
                if (!$item || !$item.length) {
                    return;
                }
                savePreviewStateForItem($item);
            }

            function applyPreviewStateForItem($item) {
                var state = getPreviewStateForItem($item);
                previewPan.x = state.panX;
                previewPan.y = state.panY;
                setPreviewScale(state.scalePercent);
            }

            function syncZoomSliderFill() {
                var min = parseFloat($zoom.attr('min')) || zoomMin;
                var max = parseFloat($zoom.attr('max')) || zoomMax;
                var val = parseFloat($zoom.val()) || min;
                var pct = Math.max(0, Math.min(100, val));
                $zoom.css('--zoom-value', pct + '%');
            }

            function getPreviewScale() {
                return currentScale;
            }

            function getPreviewDimensions() {
                var imgEl = $previewImage.get(0);
                var containerEl = $('.post-preview-media').get(0);
                if (!imgEl || !containerEl || !imgEl.naturalWidth || !imgEl.naturalHeight) {
                    return null;
                }

                var containerW = containerEl.clientWidth;
                var containerH = containerEl.clientHeight;
                var fitScale = Math.min(containerW / imgEl.naturalWidth, containerH / imgEl.naturalHeight);
                var baseW = imgEl.naturalWidth * fitScale;
                var baseH = imgEl.naturalHeight * fitScale;
                var scale = getPreviewScale();
                return {
                    containerW: containerW,
                    containerH: containerH,
                    scaledW: baseW * scale,
                    scaledH: baseH * scale,
                    fitScale: fitScale,
                    naturalW: imgEl.naturalWidth,
                    naturalH: imgEl.naturalHeight
                };
            }

            function clampPreviewPan() {
                var dims = getPreviewDimensions();
                if (!dims) {
                    previewPan.x = 0;
                    previewPan.y = 0;
                    return;
                }

                var extraX = Math.max(0, (dims.scaledW - dims.containerW) / 2);
                var extraY = Math.max(0, (dims.scaledH - dims.containerH) / 2);

                if (extraX === 0) {
                    previewPan.x = 0;
                } else {
                    previewPan.x = Math.max(-extraX, Math.min(extraX, previewPan.x));
                }

                if (extraY === 0) {
                    previewPan.y = 0;
                } else {
                    previewPan.y = Math.max(-extraY, Math.min(extraY, previewPan.y));
                }
            }

            function applyPreviewTransform() {
                clampPreviewPan();
                $previewImage.css('transform', 'translate(' + previewPan.x + 'px, ' + previewPan.y + 'px) scale(' + getPreviewScale() + ')');
            }

            function resetPreviewPan() {
                previewPan.x = 0;
                previewPan.y = 0;
                applyPreviewTransform();
            }

            function getPointerPosition(e) {
                if (e.originalEvent && e.originalEvent.touches && e.originalEvent.touches.length) {
                    return {
                        x: e.originalEvent.touches[0].clientX,
                        y: e.originalEvent.touches[0].clientY
                    };
                }

                return {
                    x: e.clientX || 0,
                    y: e.clientY || 0
                };
            }

            function setPreviewScale(percentValue) {
                var percent = Math.max(zoomMin, Math.min(zoomMax, parseFloat(percentValue) || zoomMin));
                $zoom.val(percent);
                syncZoomSliderFill();
                currentScale = percent / 100;
                applyPreviewTransform();
                saveActivePreviewState();
            }

            function setupAutoZoom() {
                var imgEl = $previewImage.get(0);
                var containerEl = $('.post-preview-media').get(0);
                if (!imgEl || !containerEl || !imgEl.naturalWidth || !imgEl.naturalHeight) {
                    $zoom.attr({ min: zoomMin, max: zoomMax, step: 1 }).prop('disabled', true);
                    setPreviewScale(zoomMin);
                    return;
                }

                $zoom.attr({ min: zoomMin, max: zoomMax, step: 1 }).prop('disabled', false);
                applyPreviewStateForItem(getActivePreviewItem());
            }

            function showUploadStep() {
                $form.removeClass('step-preview step-details').addClass('step-upload');
                $uploadStep.show();
                $previewStep.removeClass('active');
                $detailsStep.removeClass('active');
                setPreviewScale(zoomMin);
            }

            function loadPreviewImage(src) {
                $form.removeClass('step-upload step-details').addClass('step-preview');
                $uploadStep.hide();
                $detailsStep.removeClass('active');
                $previewStep.addClass('active');
                resetPreviewPan();

                $previewImage.off('load.stepzoom').on('load.stepzoom', function() {
                    setupAutoZoom();
                });
                var hasSrc = !!(src && String(src).trim());
                $previewImage.toggleClass('is-empty', !hasSrc);
                $previewImage.attr('src', hasSrc ? src : '');

                if ($previewImage.get(0).complete) {
                    setTimeout(function() {
                        setupAutoZoom();
                    }, 30);
                }
            }

            function collectPreviewSources() {
                var sources = [];
                $('#formUpdateCreate .fileuploader-item').each(function() {
                    var src = getPreviewSourceFromItem($(this));
                    if (src) {
                        // Keep one preview per selected item (do not dedupe by src).
                        sources.push(src);
                    }
                });
                return sources;
            }

            function ensurePreviewItemKey($item, index) {
                if (!$item || !$item.length) {
                    return '';
                }

                var existing = ($item.attr('data-preview-key') || '').toString();
                if (existing) {
                    return existing;
                }

                var uploadName = ($item.attr('data-upload-name') || '').toString();
                var key = uploadName ? ('file-' + uploadName) : ('idx-' + index);
                $item.attr('data-preview-key', key);
                return key;
            }

            function collectPreviewEntries() {
                var entries = [];
                $('#formUpdateCreate .fileuploader-item').each(function(index) {
                    var $item = $(this);
                    var src = getPreviewSourceFromItem($item);
                    if (!src) {
                        return;
                    }

                    entries.push({
                        key: ensurePreviewItemKey($item, index),
                        src: src
                    });
                });
                return entries;
            }

            function getActivePreviewKey() {
                var $active = getActiveUploadItem();
                if (!$active || !$active.length) {
                    return '';
                }
                return ensurePreviewItemKey($active, $active.index());
            }

            function markActiveItemByKey(key) {
                if (!key) {
                    return;
                }

                var $matched = $('#formUpdateCreate .fileuploader-item[data-preview-key="' + key.replace(/"/g, '\\"') + '"]').first();
                if (!$matched.length) {
                    return;
                }

                $('#formUpdateCreate .fileuploader-item').removeClass('preview-active');
                $matched.addClass('preview-active');
            }

            function renderPreviewThumbnails(activeSrc) {
                var entries = collectPreviewEntries();
                $previewThumbs.empty();

                var activeKey = getActivePreviewKey();

                entries.forEach(function(entry) {
                    var $btn = $('<button type="button" class="post-preview-thumb" />')
                        .attr('data-src', entry.src)
                        .attr('data-key', entry.key);
                    var $img = $('<img alt="Thumbnail">').attr('src', entry.src);
                    $btn.append($img);
                    if ((activeKey && entry.key === activeKey) || (!activeKey && entry.src === activeSrc)) {
                        $btn.addClass('active');
                    }
                    $previewThumbs.append($btn);
                });

                if (canAddMorePreviewMedia()) {
                    $previewThumbs.append(
                        $('<button type="button" class="post-preview-thumb post-preview-thumb--add"></button>')
                            .attr('aria-label', uploadMediaLabel)
                            .attr('title', uploadMediaLabel)
                    );
                }

                if ($previewThumbs.children().length) {
                    $previewThumbs.show();
                } else {
                    $previewThumbs.hide();
                }
            }

            function showPreviewStep(src) {
                var previewSrc = src || collectPreviewSources()[0] || '';
                renderPreviewThumbnails(previewSrc);
                markActiveItemBySrc(previewSrc);
                loadPreviewImage(previewSrc);
            }

            function showDetailsStep() {
                $form.removeClass('step-upload step-preview').addClass('step-details');
                $uploadStep.hide();
                $previewStep.removeClass('active');
                $detailsStep.addClass('active');
            }

            function getSelectedFilesCount() {
                var input = $('#filePhoto').get(0);
                if (input && input.files) {
                    return input.files.length;
                }

                return $('#formUpdateCreate .fileuploader-item').length;
            }

            function canAddMorePreviewMedia() {
                return !hasUploadedVideo && canAddMoreMedia();
            }

            function canAddMoreMedia() {
                var count = getSelectedFilesCount();

                if (uploadLimit > 0 && count >= uploadLimit) {
                    return false;
                }

                return true;
            }

            function openMediaPicker() {
                var input = $('#filePhoto').get(0);
                if (input && typeof input.click === 'function') {
                    input.click();
                    return;
                }

                var api = $.fileuploader.getInstance($('input[name="photo[]"]'));
                if (api && api.open) {
                    api.open();
                }
            }

            function isVideoFileName(fileName) {
                var name = (fileName || '').toString().trim().toLowerCase();
                if (!name || name.indexOf('.') === -1) {
                    return false;
                }
                var extension = name.split('.').pop();
                return videoExtensions.indexOf(extension) !== -1;
            }

            function isVideoPayload(payload) {
                if (payload) {
                    var payloadFormat = (payload.format || '').toString().toLowerCase();
                    if (payloadFormat === 'video' || payloadFormat.indexOf('video/') === 0) {
                        return true;
                    }

                    var payloadType = payload.file && payload.file.type ? payload.file.type.toString().toLowerCase() : '';
                    if (payloadType.indexOf('video/') === 0) {
                        return true;
                    }

                    if (isVideoFileName(payload.name || '')) {
                        return true;
                    }
                }

                return false;
            }

            function refreshUploadedVideoState() {
                var hasVideo = false;
                $('#formUpdateCreate .fileuploader-item').each(function() {
                    var $item = $(this);
                    var format = ($item.attr('data-upload-format') || '').toString().toLowerCase();
                    var name = ($item.attr('data-upload-name') || '').toString();
                    var mime = ($item.attr('data-upload-mime') || '').toString().toLowerCase();

                    if (format === 'video' || format.indexOf('video/') === 0 || mime.indexOf('video/') === 0 || isVideoFileName(name)) {
                        hasVideo = true;
                        return false;
                    }
                });

                hasUploadedVideo = hasVideo;
            }

            function updateUploadContinueState() {
                var count = getSelectedFilesCount();
                $form.toggleClass('has-selected-files', count > 0);
                $form.toggleClass('can-add-more-details', canAddMoreMedia());
            }

            function getPreviewSourceFromItem($item) {
                if (!$item || !$item.length) {
                    return '';
                }

                var originalSrc = $item.attr('data-original-preview-src') || '';
                if (originalSrc && !$item.attr('data-local-preview-src')) {
                    return originalSrc;
                }

                var localSrc = $item.attr('data-local-preview-src') || '';
                if (localSrc) {
                    return localSrc;
                }

                var $img = $item.find('.fileuploader-item-image img').first();
                if ($img.length) {
                    return $img.attr('src') || '';
                }

                var $canvas = $item.find('.fileuploader-item-image canvas').first();
                if ($canvas.length) {
                    return $canvas.get(0).toDataURL('image/png');
                }

                return '';
            }

            function getActiveUploadItem() {
                var $active = $('#formUpdateCreate .fileuploader-item.preview-active');
                if ($active.length) {
                    return $active;
                }

                var currentSrc = $previewImage.attr('src') || '';
                if (!currentSrc) {
                    return $();
                }

                var $match = $();
                $('#formUpdateCreate .fileuploader-item').each(function() {
                    var $item = $(this);
                    var src = getPreviewSourceFromItem($item);
                    if (src && src === currentSrc) {
                        $match = $item;
                        return false;
                    }
                });

                return $match;
            }

            function removeUploadItem($item) {
                if (!$item || !$item.length) {
                    return false;
                }

                var $removeAction = $item.find('.fileuploader-action-remove').first();
                if ($removeAction.length) {
                    $removeAction.trigger('click');
                    return true;
                }

                return false;
            }

            function markActiveItemBySrc(src) {
                if (!src) {
                    return;
                }
                var $matched = $();
                $('#formUpdateCreate .fileuploader-item').each(function() {
                    var $item = $(this);
                    if (getPreviewSourceFromItem($item) === src) {
                        $matched = $item;
                        return false;
                    }
                });
                if ($matched.length) {
                    $('#formUpdateCreate .fileuploader-item').removeClass('preview-active');
                    $matched.addClass('preview-active');
                }
            }

            function computeCropFromPreview() {
                var dims = getPreviewDimensions();
                if (!dims) {
                    return null;
                }

                var scale = getPreviewScale();
                var imgLeft = (dims.containerW / 2) + previewPan.x - (dims.scaledW / 2);
                var imgTop = (dims.containerH / 2) + previewPan.y - (dims.scaledH / 2);
                var visibleLeft = Math.max(0, -imgLeft);
                var visibleTop = Math.max(0, -imgTop);
                var visibleRight = Math.min(dims.scaledW, dims.containerW - imgLeft);
                var visibleBottom = Math.min(dims.scaledH, dims.containerH - imgTop);

                if (visibleRight <= visibleLeft || visibleBottom <= visibleTop) {
                    return null;
                }

                var factor = dims.fitScale * scale;
                var cropLeft = visibleLeft / factor;
                var cropTop = visibleTop / factor;
                var cropWidth = (visibleRight - visibleLeft) / factor;
                var cropHeight = (visibleBottom - visibleTop) / factor;

                cropLeft = Math.max(0, Math.min(dims.naturalW - 1, cropLeft));
                cropTop = Math.max(0, Math.min(dims.naturalH - 1, cropTop));
                cropWidth = Math.max(1, Math.min(dims.naturalW - cropLeft, cropWidth));
                cropHeight = Math.max(1, Math.min(dims.naturalH - cropTop, cropHeight));

                return {
                    left: Math.round(cropLeft),
                    top: Math.round(cropTop),
                    width: Math.round(cropWidth),
                    height: Math.round(cropHeight),
                    naturalW: dims.naturalW,
                    naturalH: dims.naturalH
                };
            }

            function applyLocalCropPreview($item, crop) {
                if (!$item || !$item.length || !crop) {
                    return;
                }

                var imgEl = $previewImage.get(0);
                if (!imgEl || !imgEl.naturalWidth || !imgEl.naturalHeight) {
                    return;
                }

                if (!$item.attr('data-original-preview-src')) {
                    var currentSrc = $previewImage.attr('src') || getPreviewSourceFromItem($item);
                    if (currentSrc) {
                        $item.attr('data-original-preview-src', currentSrc);
                    }
                }

                var canvas = document.createElement('canvas');
                canvas.width = crop.width;
                canvas.height = crop.height;
                var ctx = canvas.getContext('2d');
                if (!ctx) {
                    return;
                }

                ctx.drawImage(
                    imgEl,
                    crop.left,
                    crop.top,
                    crop.width,
                    crop.height,
                    0,
                    0,
                    crop.width,
                    crop.height
                );

                var dataUrl = canvas.toDataURL('image/jpeg', 0.92);
                $item.attr('data-local-preview-src', dataUrl);

                var $thumbImg = $item.find('.fileuploader-item-image img').first();
                if ($thumbImg.length) {
                    $thumbImg.attr('src', dataUrl);
                }

                var currentPreviewSrc = $previewImage.attr('src') || '';
                if (currentPreviewSrc) {
                    $previewImage.attr('src', dataUrl);
                }
            }

            function shouldPersistCrop(crop) {
                if (!crop) {
                    return false;
                }

                var margin = 2;
                if (
                    crop.left <= margin &&
                    crop.top <= margin &&
                    Math.abs(crop.width - crop.naturalW) <= margin &&
                    Math.abs(crop.height - crop.naturalH) <= margin
                ) {
                    return false;
                }

                return true;
            }

            function persistActiveCrop() {
                var $item = getActiveUploadItem();
                if (!$item || !$item.length) {
                    return $.Deferred().resolve().promise();
                }

                var format = ($item.attr('data-upload-format') || '').toString().toLowerCase();
                if (format && format !== 'image') {
                    return $.Deferred().resolve().promise();
                }

                var fileName = ($item.attr('data-upload-name') || '').toString();
                if (!fileName) {
                    return $.Deferred().resolve().promise();
                }

                var crop = computeCropFromPreview();
                if (!shouldPersistCrop(crop)) {
                    return $.Deferred().resolve().promise();
                }

                applyLocalCropPreview($item, crop);

                var payload = {
                    file: fileName,
                    crop: {
                        left: crop.left,
                        top: crop.top,
                        width: crop.width,
                        height: crop.height
                    },
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                var lastPayload = cropRequests[fileName];
                if (lastPayload &&
                    lastPayload.left === payload.crop.left &&
                    lastPayload.top === payload.crop.top &&
                    lastPayload.width === payload.crop.width &&
                    lastPayload.height === payload.crop.height) {
                    return $.Deferred().resolve().promise();
                }

                cropRequests[fileName] = payload.crop;
                return $.post(URL_BASE + '/upload/media/crop', payload).done(function(response) {
                    if (!response || !response.success || !response.file || response.file === fileName) {
                        return;
                    }

                    // Keep the client-side item bound to the latest cropped file name.
                    $item.attr('data-upload-name', response.file);
                    $item.data('upload-name', response.file);

                    cropRequests[response.file] = payload.crop;
                    delete cropRequests[fileName];
                });
            }

            // Exposed for submit flow so publish can wait until crop is persisted.
            window.persistPostCropBeforeSubmit = function() {
                return persistActiveCrop();
            };

            function openPreviewFromSelectedItem($item) {
                saveActivePreviewState();
                var src = getPreviewSourceFromItem($item);
                if (!src) {
                    return;
                }

                $('#formUpdateCreate .fileuploader-item').removeClass('preview-active');
                $item.addClass('preview-active');
                showPreviewStep(src);
            }

            function updatePriceLabel() {
                var value = ($priceInput.val() || '').trim();
                if (!value) {
                    $advancedPriceValue.text(currencySymbol + '0');
                    return;
                }
                $advancedPriceValue.text(currencySymbol + value);
            }

            function ensureLockedState(shouldLock) {
                var isLocked = $('#customCheckLocked').is(':checked');
                if (isLocked !== shouldLock) {
                    $('#customCheckLocked').prop('checked', shouldLock);
                }
            }

            function ensurePriceInputState(shouldShow) {
                var isShown = $priceInput.hasClass('active');
                if (isShown !== shouldShow) {
                    if ($('#setPrice').length) {
                        $('#setPrice').trigger('click');
                    } else {
                        $priceInput.toggleClass('active', shouldShow);
                        if (shouldShow) {
                            $('#price').stop(true, true).slideDown(100);
                        } else {
                            $('#price').stop(true, true).slideUp(100);
                            $priceInput.val('');
                        }
                    }
                }
            }

            function applyVisibilityMode(mode) {
                $visibilityMode.val(mode);
                $visibilityButtons.removeClass('active');
                $visibilityButtons.filter('[data-visibility="' + mode + '"]').addClass('active');
                $('#setSubscribersOnly').toggleClass('btn-active-hover', mode === 'subscribers');

                if (mode === 'premium') {
                    ensureLockedState(true);
                    ensurePriceInputState(true);
                } else if (mode === 'subscribers') {
                    ensureLockedState(true);
                    ensurePriceInputState(false);
                    $priceInput.val('');
                } else {
                    ensureLockedState(false);
                    ensurePriceInputState(false);
                    $priceInput.val('');
                }

                updatePriceLabel();
            }

            showUploadStep();
            updateUploadContinueState();
            if (($priceInput.val() || '').trim() !== '') {
                applyVisibilityMode('premium');
            } else if ($('#customCheckLocked').is(':checked')) {
                applyVisibilityMode('subscribers');
            } else {
                applyVisibilityMode('everyone');
            }
            updatePriceLabel();

            $(document).on('post-media-uploaded', function(e, payload) {
                $uploadOverlay.removeClass('active');
                $uploadOverlayPercent.text('100%');

                if (isVideoPayload(payload || null)) {
                    hasUploadedVideo = true;
                } else {
                    refreshUploadedVideoState();
                }

                previewUrl = null;

                if (payload && payload.previewSrc) {
                    previewUrl = payload.previewSrc;
                }

                if (!previewUrl) {
                    previewUrl = collectPreviewSources()[0] || '';
                }
                if (hasUploadedVideo) {
                    showDetailsStep();
                } else {
                    showPreviewStep(previewUrl || '');
                }
                markActiveItemBySrc(previewUrl || '');
                updateUploadContinueState();
            });

            $(document).on('post-media-removed', function() {
                previewUrl = null;
                refreshUploadedVideoState();
                $uploadOverlay.removeClass('active');
                $uploadOverlayPercent.text('0%');
                $('#formUpdateCreate .fileuploader-item').removeClass('preview-active');
                var remainingPreview = collectPreviewSources()[0] || '';

                if (getSelectedFilesCount() > 0) {
                    if (hasUploadedVideo) {
                        showDetailsStep();
                    } else {
                        showPreviewStep(remainingPreview);
                        markActiveItemBySrc(remainingPreview);
                    }
                } else {
                    showPreviewStep('');
                }

                setTimeout(function() {
                    updateUploadContinueState();
                }, 0);
            });

            $(document).on('post-media-upload-start', function() {
                $uploadOverlayPercent.text('0%');
                $uploadOverlay.addClass('active');
            });

            $(document).on('post-media-upload-progress', function(e, payload) {
                var percent = payload && typeof payload.percentage !== 'undefined' ? Math.max(0, Math.min(100, parseInt(payload.percentage, 10) || 0)) : 0;
                $uploadOverlayPercent.text(percent + '%');
            });

            $(document).on('post-media-upload-failed', function() {
                $uploadOverlay.removeClass('active');
                $uploadOverlayPercent.text('0%');
            });

            $zoom.on('input change', function() {
                setPreviewScale($(this).val());
            });

            $previewImage.on('mousedown touchstart', function(e) {
                if (!$previewStep.hasClass('active')) {
                    return;
                }

                var dims = getPreviewDimensions();
                if (!dims) {
                    return;
                }

                if (dims.scaledW <= dims.containerW && dims.scaledH <= dims.containerH) {
                    return;
                }

                isDraggingPreview = true;
                dragStart = getPointerPosition(e);
                panStart = { x: previewPan.x, y: previewPan.y };
                $previewImage.addClass('is-dragging');
                e.preventDefault();
            });

            $(document).on('mousemove touchmove', function(e) {
                if (!isDraggingPreview) {
                    return;
                }

                var point = getPointerPosition(e);
                previewPan.x = panStart.x + (point.x - dragStart.x);
                previewPan.y = panStart.y + (point.y - dragStart.y);
                applyPreviewTransform();
                e.preventDefault();
            });

            $(document).on('mouseup touchend touchcancel', function() {
                if (!isDraggingPreview) {
                    return;
                }
                isDraggingPreview = false;
                $previewImage.removeClass('is-dragging');
                saveActivePreviewState();
            });

            $(window).on('resize', function() {
                if ($previewStep.hasClass('active')) {
                    setupAutoZoom();
                }
            });

            $('#postPreviewContinue').on('click', function() {
                persistActiveCrop();
                showDetailsStep();
            });

            $('#postDetailsBack').on('click', function() {
                persistActiveCrop();
                refreshUploadedVideoState();
                var currentSrc = $previewImage.attr('src') || collectPreviewSources()[0] || '';
                if (hasUploadedVideo || !currentSrc) {
                    showUploadStep();
                    return;
                }

                showPreviewStep(currentSrc);
            });

            $('#postDetailsAddMore').on('click', function() {
                openMediaPicker();
            });

            $('#newPostHeaderBack').on('click', function(e) {
                e.preventDefault();

                if ($form.hasClass('step-details')) {
                    if (textOnlyMode && !collectPreviewSources().length) {
                        window.history.back();
                        return;
                    }
                    persistActiveCrop();
                    var currentSrc = $previewImage.attr('src') || collectPreviewSources()[0] || '';
                    showPreviewStep(currentSrc);
                    return;
                }

                if ($form.hasClass('step-preview')) {
                    showUploadStep();
                    updateUploadContinueState();
                    return;
                }

                window.history.back();
            });

            $(document).on('click', '#formUpdateCreate.step-upload .fileuploader-item', function(e) {
                if ($(e.target).closest('.fileuploader-action-remove, .fileuploader-action').length) {
                    return;
                }

                openPreviewFromSelectedItem($(this));
            });

            if (textOnlyMode) {
                $('#postDetailsBack').hide();
                $('#postDetailsAddMore').hide();
                showDetailsStep();
                setTimeout(function() {
                    $('#updateDescription').trigger('focus');
                }, 60);
            }

            $(document).on('click', '#postPreviewThumbs .post-preview-thumb', function() {
                if ($(this).hasClass('post-preview-thumb--add')) {
                    // Keep file-picker open inside the original click gesture.
                    openMediaPicker();
                    persistActiveCrop();
                    return;
                }

                var src = $(this).data('src') || '';
                if (!src) {
                    return;
                }
                saveActivePreviewState();
                persistActiveCrop();
                $('#postPreviewThumbs .post-preview-thumb').removeClass('active');
                $(this).addClass('active');
                var itemKey = ($(this).attr('data-key') || '').toString();
                if (itemKey) {
                    markActiveItemByKey(itemKey);
                } else {
                    markActiveItemBySrc(src);
                }
                loadPreviewImage(src);
            });

            $(document).on('click', '#postPreviewDelete', function() {
                var $active = getActiveUploadItem();
                if (!$active.length) {
                    $active = $('#formUpdateCreate .fileuploader-item').first();
                }

                if (!$active.length) {
                    return;
                }

                removeUploadItem($active);
            });

            $visibilityButtons.on('click', function() {
                if ($(this).is(':disabled')) {
                    return;
                }
                applyVisibilityMode($(this).data('visibility'));
            });

            $('#setSubscribersOnly').on('click', function() {
                if ($visibilityMode.val() === 'subscribers') {
                    applyVisibilityMode('everyone');
                } else {
                    applyVisibilityMode('subscribers');
                }
            });


            $priceInput.on('input change', function() {
                updatePriceLabel();
            });

            $scheduleToggle.on('change', function() {
                if (!$(this).is(':checked')) {
                    $('#inputScheduled').val('');
                    $('#dateScheduleContainer').hide();
                    $('#textPostPublish').html(publish);
                    return;
                }
                $('#modalSchedulePost').modal('show');
            });

            $(document).on('click', '#btnSubmitSchedule', function() {
                setTimeout(function() {
                    $scheduleToggle.prop('checked', !!$('#inputScheduled').val());
                }, 120);
            });

            $('#modalSchedulePost').on('hidden.bs.modal', function() {
                $scheduleToggle.prop('checked', !!$('#inputScheduled').val());
            });

            $(document).on('click', '#deleteSchedule', function() {
                $scheduleToggle.prop('checked', false);
            });
        });
    </script>
@endsection
