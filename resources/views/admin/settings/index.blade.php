@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page_title', 'Site Settings')

@section('content')
@php $tab = request('tab', 'general'); @endphp
<div class="card" style="background: var(--bg-card); padding: 24px; border-radius: 12px; border: 1px solid var(--border-color);">
    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="current_tab" value="{{ $tab }}">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
            
            @if($tab == 'general')
            {{-- GENERAL SETTINGS --}}
            <div>
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">General Settings</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="site_name" style="display:block; margin-bottom: 8px;">Site Name</label>
                    <input type="text" name="site_name" id="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'LUXE' }}" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="site_logo_dark" style="display:block; margin-bottom: 8px;">Site Logo (Dark Theme)</label>
                    @if(isset($settings['site_logo_dark']) && $settings['site_logo_dark'])
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('uploads/' . $settings['site_logo_dark']) }}" alt="Logo Dark" style="height: 50px; background: #fff; padding: 5px; border-radius: 4px;">
                        </div>
                    @endif
                    <input type="file" name="site_logo_dark" id="site_logo_dark" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="site_logo_light" style="display:block; margin-bottom: 8px;">Site Logo (White/Light Theme)</label>
                    @if(isset($settings['site_logo_light']) && $settings['site_logo_light'])
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('uploads/' . $settings['site_logo_light']) }}" alt="Logo Light" style="height: 50px; background: #1a1a1a; padding: 5px; border-radius: 4px;">
                        </div>
                    @endif
                    <input type="file" name="site_logo_light" id="site_logo_light" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="web_icon" style="display:block; margin-bottom: 8px;">Web Icon (Favicon)</label>
                    @if(isset($settings['web_icon']) && $settings['web_icon'])
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('uploads/' . $settings['web_icon']) }}" alt="Favicon" style="height: 32px; background: #fff; padding: 2px; border-radius: 4px;">
                        </div>
                    @endif
                    <input type="file" name="web_icon" id="web_icon" class="form-control" accept=".png,.ico,.jpg,.jpeg" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>
            </div>

            {{-- APPEARANCE --}}
            <div>
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Appearance</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="theme_mode" style="display:block; margin-bottom: 8px;">Default Theme Mode</label>
                    <select name="theme_mode" id="theme_mode" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                        <option value="dark" {{ ($settings['theme_mode'] ?? 'dark') == 'dark' ? 'selected' : '' }}>Dark Mode</option>
                        <option value="light" {{ ($settings['theme_mode'] ?? 'dark') == 'light' ? 'selected' : '' }}>Light Mode</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="primary_color" style="display:block; margin-bottom: 8px;">Primary Accent Color</label>
                    <input type="color" name="primary_color" id="primary_color" value="{{ $settings['primary_color'] ?? '#7c3aed' }}" style="width: 100%; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="secondary_color" style="display:block; margin-bottom: 8px;">Secondary Accent Color</label>
                    <input type="color" name="secondary_color" id="secondary_color" value="{{ $settings['secondary_color'] ?? '#a855f7' }}" style="width: 100%; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                </div>

                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-top: 32px;">Meta Data (SEO)</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="meta_title" style="display:block; margin-bottom: 8px;">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ $settings['meta_title'] ?? '' }}" placeholder="SEO Meta Title" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="meta_description" style="display:block; margin-bottom: 8px;">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" class="form-control" rows="3" placeholder="SEO Meta Description" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">{{ $settings['meta_description'] ?? '' }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="meta_keywords" style="display:block; margin-bottom: 8px;">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ $settings['meta_keywords'] ?? '' }}" placeholder="keyword1, keyword2, keyword3" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>
            </div>
            @endif

            @if($tab == 'home')
            {{-- HOME PAGE SETTINGS --}}
            <div style="grid-column: span 2;">
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Home Page — Slider Settings</h3>
                
                {{-- GLOBAL SLIDER SETTINGS --}}
                <div style="background: rgba(124,58,237,0.06); border: 1px solid rgba(124,58,237,0.2); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                    <h4 style="margin-bottom: 16px; color: var(--accent-primary); font-size: 1rem;">⚙️ Slider Behavior</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Slider Height</label>
                            <input type="text" name="slider_height" class="form-control" value="{{ $settings['slider_height'] ?? '500px' }}" placeholder="e.g. 500px or 800px" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            <small style="color: var(--text-muted); display:block; margin-top: 4px;">Auto-adjusts for mobile.</small>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Autoplay Speed (ms)</label>
                            <input type="number" name="slider_autoplay_speed" class="form-control" value="{{ $settings['slider_autoplay_speed'] ?? '5000' }}" placeholder="5000" min="1000" max="20000" step="500" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            <small style="color: var(--text-muted); display:block; margin-top: 4px;">Time between slides (1000 = 1s).</small>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Transition Effect</label>
                            <select name="slider_effect" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="fade" {{ ($settings['slider_effect'] ?? 'fade') == 'fade' ? 'selected' : '' }}>Fade (Cinematic)</option>
                                <option value="slide" {{ ($settings['slider_effect'] ?? 'fade') == 'slide' ? 'selected' : '' }}>Slide (Classic)</option>
                                <option value="creative" {{ ($settings['slider_effect'] ?? 'fade') == 'creative' ? 'selected' : '' }}>Creative (Parallax)</option>
                                <option value="cube" {{ ($settings['slider_effect'] ?? 'fade') == 'cube' ? 'selected' : '' }}>Cube (3D)</option>
                                <option value="coverflow" {{ ($settings['slider_effect'] ?? 'fade') == 'coverflow' ? 'selected' : '' }}>Coverflow (3D Flow)</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-top: 16px;">
                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Overlay Style</label>
                            <select name="slider_overlay_style" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="gradient" {{ ($settings['slider_overlay_style'] ?? 'gradient') == 'gradient' ? 'selected' : '' }}>Gradient (Bottom-up)</option>
                                <option value="dark" {{ ($settings['slider_overlay_style'] ?? 'gradient') == 'dark' ? 'selected' : '' }}>Dark Tint</option>
                                <option value="glass" {{ ($settings['slider_overlay_style'] ?? 'gradient') == 'glass' ? 'selected' : '' }}>Glassmorphism</option>
                                <option value="none" {{ ($settings['slider_overlay_style'] ?? 'gradient') == 'none' ? 'selected' : '' }}>None (Image Only)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Overlay Opacity</label>
                            <input type="range" name="slider_overlay_opacity" min="0" max="100" value="{{ $settings['slider_overlay_opacity'] ?? '50' }}" oninput="document.getElementById('opacityVal').textContent = this.value + '%'" style="width: 100%; margin-top: 6px;">
                            <small style="color: var(--text-muted); display:block; margin-top: 4px;">Current: <span id="opacityVal">{{ $settings['slider_overlay_opacity'] ?? '50' }}%</span></small>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Navigation Style</label>
                            <select name="slider_nav_style" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="both" {{ ($settings['slider_nav_style'] ?? 'both') == 'both' ? 'selected' : '' }}>Arrows + Progress Bar</option>
                                <option value="arrows" {{ ($settings['slider_nav_style'] ?? 'both') == 'arrows' ? 'selected' : '' }}>Arrows Only</option>
                                <option value="dots" {{ ($settings['slider_nav_style'] ?? 'both') == 'dots' ? 'selected' : '' }}>Dots Only</option>
                                <option value="progress" {{ ($settings['slider_nav_style'] ?? 'both') == 'progress' ? 'selected' : '' }}>Progress Bar Only</option>
                                <option value="none" {{ ($settings['slider_nav_style'] ?? 'both') == 'none' ? 'selected' : '' }}>None (Auto Only)</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px; color: var(--info);">✨ Futuristic Text Appearance</label>
                            <select name="slider_text_style" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="modern" {{ ($settings['slider_text_style'] ?? 'modern') == 'modern' ? 'selected' : '' }}>Modern Clean (Default)</option>
                                <option value="futuristic_glass" {{ ($settings['slider_text_style'] ?? 'modern') == 'futuristic_glass' ? 'selected' : '' }}>Futuristic Glassmorphism (Tech Panel)</option>
                                <option value="cyber_neon" {{ ($settings['slider_text_style'] ?? 'modern') == 'cyber_neon' ? 'selected' : '' }}>Cyber Neon (Glowing Tech)</option>
                                <option value="holographic" {{ ($settings['slider_text_style'] ?? 'modern') == 'holographic' ? 'selected' : '' }}>Holographic Minimal</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px; color: var(--info);">🚀 Text Animation</label>
                            <select name="slider_text_animation" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="fade_up" {{ ($settings['slider_text_animation'] ?? 'fade_up') == 'fade_up' ? 'selected' : '' }}>Fade Up (Smooth)</option>
                                <option value="tech_reveal" {{ ($settings['slider_text_animation'] ?? 'fade_up') == 'tech_reveal' ? 'selected' : '' }}>Tech Reveal (Data stream)</option>
                                <option value="glitch" {{ ($settings['slider_text_animation'] ?? 'fade_up') == 'glitch' ? 'selected' : '' }}>Glitch Effect</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Ken Burns Effect (Zoom)</label>
                            <select name="slider_kenburns" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="1" {{ ($settings['slider_kenburns'] ?? '1') == '1' ? 'selected' : '' }}>Enabled — Slow zoom animation</option>
                                <option value="0" {{ ($settings['slider_kenburns'] ?? '1') == '0' ? 'selected' : '' }}>Disabled — Static images</option>
                            </select>
                            <small style="color: var(--text-muted); display:block; margin-top: 4px;">Cinematic slow zoom on each slide for a premium feel.</small>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Announcement Bar Text</label>
                            <input type="text" name="announcement_text" class="form-control" value="{{ $settings['announcement_text'] ?? 'Bangladesh\'s Most Trusted E-Commerce Brand' }}" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                        </div>
                    </div>
                </div>

                {{-- PER-SLIDE SETTINGS --}}
                <div style="display: grid; grid-template-columns: 1fr; gap: 16px;">
                    @for($i = 1; $i <= 3; $i++)
                    <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); transition: border-color 0.3s ease;" 
                         onmouseover="this.style.borderColor='var(--accent-primary)'" onmouseout="this.style.borderColor='var(--border-color)'">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                            <span style="background: var(--accent-gradient); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; color: white;">{{ $i }}</span>
                            <h4 style="color: var(--text-primary); margin: 0;">Slide {{ $i }}</h4>
                            @if(isset($settings["slider_{$i}_image"]) && $settings["slider_{$i}_image"])
                                <span style="font-size: 0.75rem; padding: 2px 10px; background: rgba(16,185,129,0.15); color: #10b981; border-radius: 20px; font-weight: 600;">Active</span>
                            @else
                                <span style="font-size: 0.75rem; padding: 2px 10px; background: rgba(239,68,68,0.15); color: #ef4444; border-radius: 20px; font-weight: 600;">Empty</span>
                            @endif
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                            {{-- Image --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Image</label>
                                @if(isset($settings["slider_{$i}_image"]) && $settings["slider_{$i}_image"])
                                    <div style="margin-bottom: 10px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
                                        <img src="{{ asset('uploads/' . $settings["slider_{$i}_image"]) }}" alt="Slide {{ $i }}" style="height: 80px; width: 100%; object-fit: cover; display: block;">
                                    </div>
                                @endif
                                <input type="file" name="slider_{{ $i }}_image" class="form-control" style="width: 100%; padding: 8px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px; font-size: 0.85rem;">
                            </div>

                            {{-- Link --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Link URL</label>
                                <input type="text" name="slider_{{ $i }}_link" class="form-control" value="{{ $settings["slider_{$i}_link"] ?? '' }}" placeholder="/shop or https://..." style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            </div>

                            {{-- Text Position --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Text Position</label>
                                <select name="slider_{{ $i }}_text_position" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                    <option value="center" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'center' ? 'selected' : '' }}>Center</option>
                                    <option value="left" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'left' ? 'selected' : '' }}>Left</option>
                                    <option value="right" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'right' ? 'selected' : '' }}>Right</option>
                                    <option value="bottom-left" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'bottom-left' ? 'selected' : '' }}>Bottom Left</option>
                                    <option value="bottom-center" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'bottom-center' ? 'selected' : '' }}>Bottom Center</option>
                                </select>
                            </div>

                            {{-- Badge --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Badge Text</label>
                                <input type="text" name="slider_{{ $i }}_badge" class="form-control" value="{{ $settings["slider_{$i}_badge"] ?? '' }}" placeholder="e.g. 🔥 Hot Deal, ✨ New" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <small style="color: var(--text-muted); display:block; margin-top: 4px;">Small tag above headline.</small>
                            </div>

                            {{-- Headline --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Headline</label>
                                <input type="text" name="slider_{{ $i }}_headline" class="form-control" value="{{ $settings["slider_{$i}_headline"] ?? '' }}" placeholder="Main Headline" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            </div>

                            {{-- Button Text --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Button Text</label>
                                <input type="text" name="slider_{{ $i }}_button_text" class="form-control" value="{{ $settings["slider_{$i}_button_text"] ?? '' }}" placeholder="Shop Now" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            </div>

                            {{-- Description --}}
                            <div class="form-group" style="grid-column: span 3;">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Description</label>
                                <textarea name="slider_{{ $i }}_description" class="form-control" rows="2" placeholder="Sub-headline or description..." style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">{{ $settings["slider_{$i}_description"] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            @endif

            @if($tab == 'contact')
            {{-- CONTACT INFO --}}
            <div style="grid-column: span 2;">
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Contact Info</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="contact_email" style="display:block; margin-bottom: 8px;">Contact Email</label>
                        <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? '' }}" placeholder="support@luxestore.com" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label for="contact_phone" style="display:block; margin-bottom: 8px;">Contact Phone</label>
                        <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}" placeholder="+880 1234 567890" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    </div>
                </div>
            </div>
            @endif

            @if($tab == 'checkout')
            {{-- CHECKOUT SETTINGS --}}
            <div style="grid-column: span 2;">
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Checkout Settings</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="cod_banner_title" style="display:block; margin-bottom: 8px;">COD Banner Title</label>
                    <input type="text" name="cod_banner_title" id="cod_banner_title" class="form-control" value="{{ $settings['cod_banner_title'] ?? 'Cash on Delivery' }}" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="cod_banner_text" style="display:block; margin-bottom: 8px;">COD Banner Text</label>
                    <textarea name="cod_banner_text" id="cod_banner_text" class="form-control" rows="2" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">{{ $settings['cod_banner_text'] ?? 'Pay when you receive your order. No online payment hassle — just shop, checkout, and pay at your doorstep.' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="checkout_warning_text" style="display:block; margin-bottom: 8px;">Checkout Warning Text</label>
                    <textarea name="checkout_warning_text" id="checkout_warning_text" class="form-control" placeholder="e.g. Please double check your phone number..." style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px; min-height: 80px;">{{ $settings['checkout_warning_text'] ?? '' }}</textarea>
                    <small style="color: var(--text-muted); display:block; margin-top: 4px;">This text will appear as a warning box at the top of the checkout form.</small>
                </div>
            </div>
            @endif

            @if($tab == 'tracking')
            {{-- TRACKING AND API --}}
            <div style="grid-column: span 2;">
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Tracking & Integrations</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="fb_pixel_id" style="display:block; margin-bottom: 8px;">Facebook Pixel ID</label>
                        <input type="text" name="fb_pixel_id" id="fb_pixel_id" class="form-control" value="{{ $settings['fb_pixel_id'] ?? '' }}" placeholder="e.g., 123456789012345" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    </div>

                    <div class="form-group">
                        <label for="gtm_id" style="display:block; margin-bottom: 8px;">Google Tag Manager ID</label>
                        <input type="text" name="gtm_id" id="gtm_id" class="form-control" value="{{ $settings['gtm_id'] ?? '' }}" placeholder="e.g., GTM-XXXXXXX" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="capi_token" style="display:block; margin-bottom: 8px;">Facebook Conversions API (CAPI) Token</label>
                        <input type="text" name="capi_token" id="capi_token" class="form-control" value="{{ $settings['capi_token'] ?? '' }}" placeholder="Long CAPI Token string..." style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                        <small style="color: var(--text-muted); display:block; margin-top: 4px;">Currently stored for future backend event integrations.</small>
                    </div>
                </div>
            </div>
            @endif

        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; text-align: right;">
            <button type="submit" class="btn" style="background: var(--accent-gradient); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
