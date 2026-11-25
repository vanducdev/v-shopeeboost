/**
 * V-ShopeeBoost v6.0.0 Pro Max — Fingerprint & Anti-Detection Engine
 * Injected in the MAIN world context at document_start to override native APIs cleanly and smoothly.
 */
(function() {
  'use strict';

  // Check if FINGERPRINT_CONFIG is defined
  if (typeof FINGERPRINT_CONFIG === 'undefined' || !FINGERPRINT_CONFIG || Object.keys(FINGERPRINT_CONFIG).length === 0) {
    return;
  }

  // Disabling all overrides for Shopee domains to ensure captcha and page compatibility
  if (window.location.host.includes('shopee.')) {
    return;
  }

  const isVerificationPage = /captcha|verify|challenge|security|recaptcha|hcaptcha|turnstile/i.test(window.location.href);

  // 1. Hide Webdriver automation flag
  try {
    if (navigator.webdriver !== undefined) {
      Object.defineProperty(navigator, 'webdriver', {
        get: () => false,
        configurable: true
      });
    }
  } catch (e) {}

  // 2. User Agent & Platform
  try {
    if (FINGERPRINT_CONFIG.userAgent) {
      Object.defineProperty(navigator, 'userAgent', {
        get: () => FINGERPRINT_CONFIG.userAgent,
        configurable: true
      });
    }
    if (FINGERPRINT_CONFIG.platform) {
      Object.defineProperty(navigator, 'platform', {
        get: () => FINGERPRINT_CONFIG.platform,
        configurable: true
      });
    }
  } catch (e) {}

  // 3. Hardware Specs: CPU & RAM
  try {
    if (FINGERPRINT_CONFIG.cpu && FINGERPRINT_CONFIG.cpu !== 'random') {
      const cpuVal = parseInt(FINGERPRINT_CONFIG.cpu);
      if (!isNaN(cpuVal)) {
        Object.defineProperty(navigator, 'hardwareConcurrency', {
          get: () => cpuVal,
          configurable: true
        });
      }
    }
    if (FINGERPRINT_CONFIG.ram && FINGERPRINT_CONFIG.ram !== 'random') {
      const ramVal = parseInt(FINGERPRINT_CONFIG.ram);
      if (!isNaN(ramVal)) {
        Object.defineProperty(navigator, 'deviceMemory', {
          get: () => ramVal,
          configurable: true
        });
      }
    }
  } catch (e) {}

  // 4. Screen Resolution
  try {
    if (FINGERPRINT_CONFIG.screen && FINGERPRINT_CONFIG.screen !== 'random') {
      const screenParts = String(FINGERPRINT_CONFIG.screen).split(/[x,]/).map(s => parseInt(s.trim(), 10)).filter(n => !isNaN(n) && n > 0);
      if (screenParts.length >= 2) {
        const [w, h] = screenParts;
        const availH = Math.max(0, h - 40);
        Object.defineProperty(window.screen, 'width', { get: () => w, configurable: true });
        Object.defineProperty(window.screen, 'height', { get: () => h, configurable: true });
        Object.defineProperty(window.screen, 'availWidth', { get: () => w, configurable: true });
        Object.defineProperty(window.screen, 'availHeight', { get: () => availH, configurable: true });
        Object.defineProperty(window.screen, 'colorDepth', { get: () => 24, configurable: true });
        Object.defineProperty(window.screen, 'pixelDepth', { get: () => 24, configurable: true });
      }
    }
  } catch (e) {}

  // 5. Languages
  try {
    if (FINGERPRINT_CONFIG.language) {
      const langs = FINGERPRINT_CONFIG.language.split(',').map(l => l.trim()).filter(Boolean);
      if (langs.length > 0) {
        Object.defineProperty(navigator, 'languages', {
          get: () => langs,
          configurable: true
        });
        Object.defineProperty(navigator, 'language', {
          get: () => langs[0],
          configurable: true
        });
      }
    }
  } catch (e) {}

  // 6. Timezone Overrides & Offset Alignment
  try {
    if (FINGERPRINT_CONFIG.timezone && FINGERPRINT_CONFIG.timezone !== 'auto') {
      const targetTz = FINGERPRINT_CONFIG.timezone;
      const orgResolvedOptions = Intl.DateTimeFormat.prototype.resolvedOptions;
      Intl.DateTimeFormat.prototype.resolvedOptions = function() {
        const opts = orgResolvedOptions.apply(this);
        opts.timeZone = targetTz;
        return opts;
      };

      try {
        const now = new Date();
        const str = now.toLocaleTimeString('en-US', { timeZone: targetTz, timeZoneName: 'longOffset' });
        const match = str.match(/GMT([+-]\d{2}):(\d{2})/);
        if (match) {
          const sign = match[1][0] === '+' ? -1 : 1;
          const h = parseInt(match[1].slice(1), 10);
          const m = parseInt(match[2], 10);
          const targetOffset = sign * (h * 60 + m);
          Date.prototype.getTimezoneOffset = function() {
            return targetOffset;
          };
        }
      } catch (e) {}
    }
  } catch (e) {}

  // 7. WebRTC Leak Shield
  try {
    const webrtcSetting = FINGERPRINT_CONFIG.webrtc || 'mask';
    if (webrtcSetting === 'disable') {
      if (window.RTCPeerConnection) window.RTCPeerConnection = undefined;
      if (window.webkitRTCPeerConnection) window.webkitRTCPeerConnection = undefined;
    } else if (webrtcSetting === 'mask' && window.RTCPeerConnection) {
      const OriginalRTCPeerConnection = window.RTCPeerConnection;
      window.RTCPeerConnection = function(config, ...args) {
        const pc = new OriginalRTCPeerConnection(config, ...args);
        const originalAddIceCandidate = pc.addIceCandidate;
        pc.addIceCandidate = function(candidate, ...innerArgs) {
          if (candidate && candidate.candidate) {
            if (/(\b192\.168\.\d+\.\d+\b|\b10\.\d+\.\d+\.\d+\b|\b172\.(1[6-9]|2[0-9]|3[0-1])\.\d+\.\d+\b)/.test(candidate.candidate)) {
              return Promise.resolve();
            }
          }
          return originalAddIceCandidate.call(this, candidate, ...innerArgs);
        };
        return pc;
      };
      window.RTCPeerConnection.prototype = OriginalRTCPeerConnection.prototype;
      if (window.webkitRTCPeerConnection) {
        window.webkitRTCPeerConnection = window.RTCPeerConnection;
      }
    }
  } catch (e) {}

  // Context Cache to keep track of active canvas context type (2d vs webgl)
  const canvasContextCache = new WeakMap();
  try {
    const originalGetContext = HTMLCanvasElement.prototype.getContext;
    HTMLCanvasElement.prototype.getContext = function(type, ...args) {
      const ctx = originalGetContext.call(this, type, ...args);
      if (ctx) {
        canvasContextCache.set(this, type);
      }
      return ctx;
    };
  } catch (e) {}

  // 6. Canvas Fingerprint Noise
  if (!isVerificationPage) {
    try {
      if (FINGERPRINT_CONFIG.canvas === 'noise') {
        const originalToDataURL = HTMLCanvasElement.prototype.toDataURL;
        HTMLCanvasElement.prototype.toDataURL = function(...args) {
          // Only modify pixels if the canvas has initialized a 2D context
          // Calling getContext('2d') on a WebGL canvas triggers duplicate context warnings
          if (canvasContextCache.get(this) === '2d') {
            const ctx = this.getContext('2d');
            if (ctx) {
              try {
                const originalFillStyle = ctx.fillStyle;
                ctx.fillStyle = 'rgba(255,255,255,0.01)';
                ctx.fillRect(0, 0, 1, 1);
                ctx.fillStyle = originalFillStyle;
              } catch(e) {}
            }
          }
          return originalToDataURL.apply(this, args);
        };

        const originalGetImageData = CanvasRenderingContext2D.prototype.getImageData;
        CanvasRenderingContext2D.prototype.getImageData = function(x, y, w, h) {
          const imgData = originalGetImageData.call(this, x, y, w, h);
          const data = imgData.data;
          // Introduce micro noise (modify 1 in every 4096 pixels)
          for (let i = 0; i < data.length; i += 4096) {
            data[i] = (data[i] + 1) % 256;
          }
          return imgData;
        };
      }
    } catch (e) {}
  }

  // 10. WebGL GPU Spoofing
  if (!isVerificationPage) {
    try {
      if (FINGERPRINT_CONFIG.gpu && FINGERPRINT_CONFIG.gpu !== 'random') {
        const fakeGpu = FINGERPRINT_CONFIG.gpu;
        const overrideWebGL = (proto) => {
          if (!proto) return;
          const originalGetParameter = proto.getParameter;
          proto.getParameter = function(pname) {
            // UNMASKED_VENDOR_WEBGL = 0x9245
            // UNMASKED_RENDERER_WEBGL = 0x9246
            if (pname === 0x9245) {
              return 'Google Inc. (Intel)';
            }
            if (pname === 0x9246) {
              return fakeGpu;
            }
            return originalGetParameter.call(this, pname);
          };
        };
        
        if (window.WebGLRenderingContext) overrideWebGL(WebGLRenderingContext.prototype);
        if (window.WebGL2RenderingContext) overrideWebGL(WebGL2RenderingContext.prototype);
      }
    } catch (e) {}
  }

  // 11. Audio Fingerprint Noise (Smooth, Zero Stutter)
  if (!isVerificationPage && FINGERPRINT_CONFIG.audio === 'noise') {
    try {
      if (window.AudioBuffer) {
        const originalGetChannelData = AudioBuffer.prototype.getChannelData;
        AudioBuffer.prototype.getChannelData = function(channel) {
          const channelData = originalGetChannelData.call(this, channel);
          for (let i = 0; i < channelData.length; i += 500) {
            channelData[i] += 0.0000001;
          }
          return channelData;
        };
      }
    } catch (e) {}
  }

})();
