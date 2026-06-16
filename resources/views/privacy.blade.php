@extends('layouts.marketing')

@section('title')
    <title>{{ config('app.name') }} | Terms & Privacy</title>
@endsection

@section('content')
    <!-- Legal Content -->
    <main class="relative pt-32 pb-24 lg:pt-40 px-6">
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[400px] fluid-bg-soft opacity-20 blur-[100px] rounded-full z-0 pointer-events-none">
        </div>

        <article class="relative z-10 max-w-3xl mx-auto">
            <header class="mb-12 pb-8 border-b border-gray-200">
                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight mb-3">{{ config('app.name') }} Terms & Privacy</h1>
                <p class="text-sm text-textMuted">Last Updated: Monday, June 15, 2026</p>
            </header>

            <p class="text-lg text-textMuted leading-relaxed mb-12">
                Welcome to {{ config('app.name') }}. This page contains our Terms of Service and our Privacy Policy. By
                accessing
                or using our application, you agree to be bound by these terms and policies.
            </p>

            <!-- Part 1: Terms of Service -->
            <section id="terms" class="mb-16">
                <h2 class="text-2xl lg:text-3xl font-bold mb-8 pb-3 border-b border-gray-100">Part 1: Terms of Service
                </h2>

                <div class="space-y-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-3">1. Acceptance of Terms</h3>
                        <p class="text-textMuted leading-relaxed">
                            By creating an account, accessing, or using {{ config('app.name') }} (the "Service"), you agree
                            to
                            these Terms of Service. If you do not agree to these terms, please do not use the Service.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">2. Description of Service</h3>
                        <p class="text-textMuted leading-relaxed">
                            {{ config('app.name') }} is a cloud-based project management tool designed to help individuals
                            and
                            teams organize tasks, collaborate, and manage workflows.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">3. User Accounts</h3>
                        <ul class="space-y-3 text-textMuted leading-relaxed">
                            <li><strong class="text-textDark font-medium">Registration:</strong> You must provide
                                accurate and complete information when creating an account.</li>
                            <li><strong class="text-textDark font-medium">Security:</strong> You are responsible for
                                safeguarding the password that you use to access the Service and for any activities or
                                actions under your password.</li>
                            <li><strong class="text-textDark font-medium">Termination:</strong> We reserve the right to
                                suspend or terminate your account at any time for violations of these terms.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">4. Acceptable Use</h3>
                        <p class="text-textMuted leading-relaxed mb-3">You agree not to use the Service to:</p>
                        <ul class="list-disc list-inside space-y-2 text-textMuted leading-relaxed ml-2">
                            <li>Violate any local, state, national, or international law.</li>
                            <li>Infringe upon the intellectual property rights of others.</li>
                            <li>Upload or transmit viruses, malware, or any other malicious code.</li>
                            <li>Attempt to gain unauthorized access to the Service or its related systems.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">5. Intellectual Property</h3>
                        <p class="text-textMuted leading-relaxed">
                            All content, features, and functionality of the Service are owned by {{ config('app.name') }} and are
                            protected by
                            copyright and intellectual property laws. You retain all rights to the data and content you
                            upload to the Service ("User Content").
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">6. Limitation of Liability</h3>
                        <p class="text-textMuted leading-relaxed">
                            To the maximum extent permitted by law, {{ config('app.name') }} shall not be liable for any
                            indirect, incidental, special, consequential, or punitive damages, or any loss of profits or
                            revenues, whether incurred directly or indirectly, or any loss of data, use, goodwill, or
                            other intangible losses resulting from your use of the Service.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Part 2: Privacy Policy -->
            <section id="privacy" class="mb-16">
                <h2 class="text-2xl lg:text-3xl font-bold mb-8 pb-3 border-b border-gray-100">Part 2: Privacy Policy
                </h2>

                <div class="space-y-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-3">1. Information We Collect</h3>
                        <p class="text-textMuted leading-relaxed mb-3">
                            We collect the following types of information to provide and improve our Service:
                        </p>
                        <ul class="space-y-3 text-textMuted leading-relaxed">
                            <li><strong class="text-textDark font-medium">Personal Information:</strong> Email address and
                                name provided during account registration.</li>
                            <li><strong class="text-textDark font-medium">User Content:</strong> The tasks, projects, and
                                files you create and store in the platform.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">2. How We Use Your Information</h3>
                        <p class="text-textMuted leading-relaxed mb-3">We use the collected information for various
                            purposes:</p>
                        <ul class="list-disc list-inside space-y-2 text-textMuted leading-relaxed ml-2">
                            <li>To provide, maintain, and improve the Service.</li>
                            <li>To manage your account and provide customer support.</li>
                            <li>To communicate with you about updates, security alerts, and administrative messages.
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">3. Data Sharing and Disclosure</h3>
                        <p class="text-textMuted leading-relaxed mb-3">
                            We do not sell your personal information. We may share your information only in the
                            following circumstances:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-textMuted leading-relaxed ml-2">
                            <li>With your consent or at your direction (e.g., when you invite a team member to
                                collaborate).</li>
                            <li>With service providers who assist us in operating our business (e.g., hosting providers,
                                payment processors), under strict confidentiality agreements.</li>
                            <li>To comply with legal obligations, enforce our Terms of Service, or protect the rights,
                                property, or safety of {{ config('app.name') }}, our users, or others.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">4. Data Security</h3>
                        <p class="text-textMuted leading-relaxed">
                            We implement commercially reasonable security measures to protect your personal information.
                            However, no method of transmission over the Internet or electronic storage is 100% secure,
                            and we cannot guarantee absolute security.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">5. Your Rights</h3>
                        <p class="text-textMuted leading-relaxed mb-3">
                            Depending on your location (e.g., under GDPR or CCPA), you may have the right to:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-textMuted leading-relaxed ml-2 mb-3">
                            <li>Access the personal data we hold about you.</li>
                            <li>Request correction of inaccurate data.</li>
                            <li>Request deletion of your data.</li>
                            <li>Opt-out of certain data collection or marketing communications.</li>
                        </ul>
                        <p class="text-textMuted leading-relaxed">
                            To exercise these rights, please contact us at <a href="mailto:aaron@ironmthome.com"
                                class="text-appPurple hover:underline">aaron@ironmthome.com</a>.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">6. Cookies</h3>
                        <p class="text-textMuted leading-relaxed">
                            We use cookies and similar tracking technologies to track activity on our Service and hold
                            certain information to improve your experience. You can instruct your browser to refuse all
                            cookies or to indicate when a cookie is being sent.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Contact -->
            <section id="contact" class="pt-8 border-t border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Contact Us</h2>
                <p class="text-textMuted leading-relaxed mb-4">
                    If you have any questions about these Terms of Service or Privacy Policy, please contact us at <a
                        href="mailto:aaron@ironmthome.com" class="text-appPurple hover:underline">aaron@ironmthome.com</a>.
                </p>
            </section>
        </article>
    </main>
@endsection
