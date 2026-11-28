<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Console') | NR Fitness</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-rounded.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    @stack('styles')
</head>

<body>
    <div id="wrapper">
        @include('layouts.admin.sidebar')
        <button id="sidebar-overlay" class="sidebar-overlay" type="button" aria-label="Close navigation"></button>

        <div id="content">
            <header class="topbar">
                <button id="menu-toggle" class="toggle-btn" type="button" aria-label="Toggle navigation" aria-expanded="true">
                    <i class="bi bi-list"></i>
                </button>
                <div class="topbar-context">
                    <span class="eyebrow">NR FITNESS / ADMIN</span>
                    <span class="topbar-title">@yield('page-heading', 'Admin overview')</span>
                </div>
                <div class="topbar-right">
                    <span class="status-indicator"><span></span> System online</span>
                    <div class="topbar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                </div>
            </header>

            <main class="admin-main">
                @if (session('success'))
                    <div class="alert admin-alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert admin-alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert admin-alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            <strong>Please review the form:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <div class="modal fade" id="formConfirmationModal" tabindex="-1" aria-labelledby="formConfirmationTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="formConfirmationTitle">Confirm action</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="formConfirmationMessage"></div>
                <div class="modal-footer">
                    <button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="admin-btn" id="formConfirmationSubmit">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const adminWrapper = document.getElementById('wrapper');
        const menuToggle = document.getElementById('menu-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileNavigation = window.matchMedia('(max-width: 767.98px)');

        function updateNavigationState() {
            const isOpen = mobileNavigation.matches
                ? adminWrapper.classList.contains('collapsed')
                : !adminWrapper.classList.contains('collapsed');
            menuToggle.setAttribute('aria-expanded', String(isOpen));
        }

        menuToggle.addEventListener('click', function () {
            adminWrapper.classList.toggle('collapsed');
            updateNavigationState();
        });

        sidebarOverlay.addEventListener('click', function () {
            adminWrapper.classList.remove('collapsed');
            updateNavigationState();
        });

        mobileNavigation.addEventListener('change', updateNavigationState);
        updateNavigationState();

        const formConfirmationElement = document.getElementById('formConfirmationModal');
        const formConfirmation = bootstrap.Modal.getOrCreateInstance(formConfirmationElement);
        const formConfirmationTitle = document.getElementById('formConfirmationTitle');
        const formConfirmationMessage = document.getElementById('formConfirmationMessage');
        const formConfirmationSubmit = document.getElementById('formConfirmationSubmit');
        let pendingConfirmationForm = null;

        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm-message')) {
                return;
            }

            if (form.dataset.confirmed === 'true') {
                delete form.dataset.confirmed;
                return;
            }

            event.preventDefault();
            pendingConfirmationForm = form;
            formConfirmationTitle.textContent = form.dataset.confirmTitle || 'Confirm action';
            formConfirmationMessage.textContent = form.dataset.confirmMessage;
            formConfirmationSubmit.textContent = form.dataset.confirmLabel || 'Continue';
            formConfirmationSubmit.className = form.dataset.confirmVariant === 'danger'
                ? 'btn btn-danger'
                : 'admin-btn';
            formConfirmation.show();
        });

        formConfirmationSubmit.addEventListener('click', function () {
            if (!pendingConfirmationForm) {
                return;
            }

            const form = pendingConfirmationForm;
            pendingConfirmationForm = null;
            form.dataset.confirmed = 'true';
            formConfirmation.hide();
            form.requestSubmit();
        });

        formConfirmationElement.addEventListener('hidden.bs.modal', function () {
            pendingConfirmationForm = null;
        });

        document.addEventListener('submit', function (event) {
            const form = event.target;
            const modal = form instanceof HTMLFormElement ? form.closest('.modal') : null;
            if (!modal || form.elements.namedItem('_validation_form')) {
                return;
            }

            const marker = document.createElement('input');
            marker.type = 'hidden';
            marker.name = '_validation_form';
            marker.value = modal.id;
            form.appendChild(marker);
        });

        const validationMessages = @json($errors->getMessages());
        const validationModalId = @json(old('_validation_form'));
        const validationModal = validationModalId ? document.getElementById(validationModalId) : null;
        const validationScope = validationModal || document;
        const normalizeFieldName = name => name.replace(/\[\]/g, '').replace(/\[(\d+)\]/g, '.$1');

        Object.entries(validationMessages).forEach(([name, messages]) => {
            const normalizedName = normalizeFieldName(name);
            const baseName = normalizedName.replace(/\.\d+$/, '');
            const fields = Array.from(validationScope.querySelectorAll('[name]')).filter(field => {
                const fieldName = normalizeFieldName(field.getAttribute('name'));
                return fieldName === normalizedName || fieldName === baseName || fieldName.startsWith(normalizedName + '.');
            });
            const feedbackGroups = new Set();

            fields.forEach(field => {
                field.classList.add('is-invalid');
                field.setAttribute('aria-invalid', 'true');
                const group = field.closest('.d-flex.flex-wrap.gap-3')
                    || field.closest('.mb-3')
                    || field.closest('[class*="col-"]')
                    || field.parentElement;
                if (group) {
                    feedbackGroups.add(group);
                }
            });

            feedbackGroups.forEach((group, index) => {
                const feedback = document.createElement('div');
                feedback.className = 'admin-inline-feedback';
                feedback.id = 'validation-feedback-' + name.replace(/[^a-zA-Z0-9_-]/g, '-') + '-' + index;
                feedback.textContent = messages[0];
                group.appendChild(feedback);
                fields.filter(field => group.contains(field)).forEach(field => {
                    const describedBy = field.getAttribute('aria-describedby');
                    field.setAttribute('aria-describedby', [describedBy, feedback.id].filter(Boolean).join(' '));
                });
            });
        });

        if (validationModal) {
            const showValidationModal = function () {
                bootstrap.Modal.getOrCreateInstance(validationModal).show();
            };
            const tabPane = validationModal.closest('.tab-pane');
            if (tabPane && !tabPane.classList.contains('active')) {
                const tab = document.querySelector('[data-bs-target="#' + tabPane.id + '"]');
                if (tab) {
                    tab.addEventListener('shown.bs.tab', showValidationModal, { once: true });
                    bootstrap.Tab.getOrCreateInstance(tab).show();
                }
            } else {
                showValidationModal();
            }
        }
    </script>
    @stack('scripts')
</body>

</html>
