document.documentElement.classList.add('has-js');

const themeStorageKey = 'member-desk-theme';
const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
let savedTheme = null;

try {
    savedTheme = window.localStorage.getItem(themeStorageKey);
} catch {
    savedTheme = null;
}

const applyTheme = (theme) => {
    document.documentElement.dataset.theme = theme;

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        const nextTheme = theme === 'dark' ? 'light' : 'dark';

        button.setAttribute('aria-label', `Switch to ${nextTheme} mode`);
        button.querySelector('[data-theme-label]').textContent = `${nextTheme === 'dark' ? 'Dark' : 'Light'} mode`;
    });
};

applyTheme(savedTheme === 'light' || savedTheme === 'dark' ? savedTheme : (prefersDarkMode ? 'dark' : 'light'));

document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';

        try {
            window.localStorage.setItem(themeStorageKey, nextTheme);
        } catch {
            // The theme still changes for this page when storage is unavailable.
        }

        applyTheme(nextTheme);
    });
});

const loginInfoToggle = document.querySelector('[data-login-info-toggle]');
const loginInfoPanel = document.querySelector('#login-info-panel');

loginInfoToggle?.addEventListener('click', () => {
    const isExpanded = loginInfoToggle.getAttribute('aria-expanded') === 'true';

    loginInfoToggle.setAttribute('aria-expanded', String(! isExpanded));
    loginInfoToggle.textContent = isExpanded ? 'Show demo login info' : 'Hide demo login info';
    loginInfoPanel.hidden = isExpanded;
});

const revealObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
        if (!entry.isIntersecting) {
            return;
        }

        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
    });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal, .shutter').forEach((element) => {
    revealObserver.observe(element);
});

const levels = {
    Basic: { fee: '₱500 / year', benefit: 'Member directory access', color: '#bcdcee' },
    Silver: { fee: '₱750 / year', benefit: 'Directory and event access', color: '#cbe4f2' },
    Gold: { fee: '₱1,200 / year', benefit: 'Priority events and partner offers', color: '#ffc013' },
    Platinum: { fee: '₱2,500 / year', benefit: 'All benefits and priority support', color: '#f8e7f3' },
};

const levelButtons = document.querySelectorAll('[data-level]');
const levelName = document.querySelector('[data-level-name]');
const levelFee = document.querySelector('[data-level-fee]');
const levelBenefit = document.querySelector('[data-level-benefit]');
const membershipDisc = document.querySelector('[data-membership-disc]');

levelButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const selectedLevel = button.dataset.level;
        const level = levels[selectedLevel];

        levelButtons.forEach((item) => item.setAttribute('aria-pressed', 'false'));
        button.setAttribute('aria-pressed', 'true');

        if (levelName && levelFee && levelBenefit && membershipDisc) {
            levelName.textContent = selectedLevel;
            levelFee.textContent = level.fee;
            levelBenefit.textContent = level.benefit;
            membershipDisc.style.backgroundColor = level.color;
        }
    });
});

const workflowRange = document.querySelector('[data-workflow-range]');
const workflowTitle = document.querySelector('[data-workflow-title]');
const workflowStatus = document.querySelector('[data-workflow-status]');
const workflowNote = document.querySelector('[data-workflow-note]');
const workflowSteps = [
    ['Add the essentials', 'Profile ready', 'Name, contact details, category, and level are stored together.'],
    ['Set the membership', 'Fee recorded', 'Fee, validity period, and benefits stay attached to the member.'],
    ['Keep status clear', 'Active', 'A single status shows who is current and who needs follow-up.'],
    ['Move work forward', 'Task saved', 'Everyday follow-ups sit beside the membership directory.'],
];

workflowRange?.addEventListener('input', (event) => {
    const step = workflowSteps[Number(event.target.value)];

    if (workflowTitle && workflowStatus && workflowNote) {
        workflowTitle.textContent = step[0];
        workflowStatus.textContent = step[1];
        workflowNote.textContent = step[2];
    }
});

const membershipMap = document.querySelector('[data-membership-map]');
const latitudeField = document.querySelector('#latitude');
const longitudeField = document.querySelector('#longitude');
const weatherMessage = document.querySelector('[data-weather-message]');
const mapMessage = document.querySelector('[data-map-message]');
let membershipMarker = null;
let weatherRequest = null;

const loadSelectedLocationWeather = async () => {
    if (! latitudeField || ! longitudeField || ! weatherMessage) {
        return;
    }

    if (! latitudeField.value || ! longitudeField.value) {
        weatherMessage.textContent = 'Select a map point to check local weather.';

        return;
    }

    weatherRequest?.abort();
    weatherRequest = new AbortController();
    weatherMessage.textContent = 'Loading current weather…';

    try {
        const response = await fetch(`/weather?lat=${encodeURIComponent(latitudeField.value)}&lon=${encodeURIComponent(longitudeField.value)}`, { signal: weatherRequest.signal });
        const weather = await response.json();

        if (! response.ok) {
            throw new Error(weather.message || 'Weather is unavailable.');
        }

        weatherMessage.textContent = `${weather.location}: ${weather.temperature}°C, ${weather.description}. Humidity ${weather.humidity}%.`;
    } catch (error) {
        if (error.name !== 'AbortError') {
            weatherMessage.textContent = error.message;
        }
    }
};

window.initMembershipMap = async () => {
    if (! membershipMap || ! window.google?.maps) {
        return;
    }

    const initialPosition = {
        lat: Number(latitudeField.value) || 14.5995,
        lng: Number(longitudeField.value) || 120.9842,
    };
    const map = new google.maps.Map(membershipMap, { center: initialPosition, zoom: 12, mapTypeControl: false, streetViewControl: false });
    membershipMarker = new google.maps.Marker({ position: initialPosition, map });
    mapMessage.textContent = 'Click the map to set your coordinates.';

    map.addListener('click', (event) => {
        latitudeField.value = event.latLng.lat().toFixed(7);
        longitudeField.value = event.latLng.lng().toFixed(7);
        membershipMarker.setPosition(event.latLng);
        loadSelectedLocationWeather();
    });
};

[latitudeField, longitudeField].forEach((field) => field?.addEventListener('change', () => {
    if (membershipMarker) {
        membershipMarker.setPosition({ lat: Number(latitudeField.value), lng: Number(longitudeField.value) });
    }

    loadSelectedLocationWeather();
}));

loadSelectedLocationWeather();
