<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Innovexa Labs Home Page">
        <meta name="keywords" content="Innovexa, Labs, Index, Home">
        <meta name="author" content="G05">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Innovexa Labs</title>

        <!-- External stylesheet -->
        <link rel="stylesheet" href="styles/styles.css">
    </head>

    <body>
        <!-- ================== HEADER ================== -->
        <?php include_once "header.inc"; ?>

        <!-- ================= MAIN CONTENT ================= -->
        <main>
            <!-- Intro section -->
            <h2>What Is Innovexa Labs?</h2>
            <section>
                <p>
                    At Innovexa Labs, technology and design are not separate — they evolve together.
                    We believe great products happen when strong engineering meets human-centred design.
                    From scalable design systems and reusable components to intuitive analytics dashboards,
                    our team crafts experiences that are accessible (WCAG-minded), reliable, and impactful.
                </p>
            </section>

            <!-- ===== Hero section with background image and call-to-actions ===== -->
            <section class="hero" style="background-image: url('images/AdobeStock_994259841.jpeg');">
                <div class="hero__content" role="region" aria-label="Innovexa Labs intro">
                    <p class="eyebrow">Where Technology Meets Design</p>
                    <h1 class="hero__title">Design Systems. Accessible Tech. Real Impact.</h1>
                    <p class="hero__sub">
                        We build component libraries, lead design sprints, and ship WCAG-compliant interfaces —
                        all backed by dependable engineering and measurable outcomes.
                    </p>
                    <div class="hero__cta">
                        <!-- Primary CTA -->
                        <a class="btn" href="jobs.php">See Open Roles</a>
                        <!-- Secondary CTA -->
                        <a class="btn btn--ghost" href="about.php">Learn About Us</a>
                    </div>
                </div>
            </section>

            <!-- ===== Photo gallery showcasing company culture ===== -->
            <section class="gallery" aria-labelledby="gallery-title">
                <h2 id="gallery-title">Inside Innovexa</h2>
                <p class="section-lead">A glimpse of our build culture — discovery, prototyping, and systemised delivery.</p>

                <!-- Grid of gallery cards -->
                <div class="gallery__grid">
                    <figure class="card">
                        <img src="images/AdobeStock_994259841.jpeg" alt="Immersive VR sketch illustration">
                        <figcaption>Design discovery: sketch, critique, iterate.</figcaption>
                        <p class="card-note">We start with discovery sessions and turn customer insights into user flows, wireframes, and testable prototypes that guide engineering.</p>
                    </figure>

                    <figure class="card">
                        <img src="images/AdobeStock_1062020786.jpeg" alt="Virtual reality explosion concept">
                        <figcaption>Turning complexity into intuitive visuals.</figcaption>
                        <p class="card-note">From complex analytics to clear dashboards — we simplify information with accessible UI patterns and measurable product metrics.</p>
                    </figure>

                    <figure class="card">
                        <img src="images/AdobeStock_758326186.jpeg" alt="Two robots hugging in a friendly way">
                        <figcaption>Human-centred robotics and inclusive futures.</figcaption>
                        <p class="card-note">Design × Engineering collaboration is our default: shared specs, documented components, and mentorship across teams.</p>
                    </figure>

                    <figure class="card">
                        <img src="images/AdobeStock_668332371.jpeg" alt="Person in VR with paint splashes">
                        <figcaption>Creativity meets engineered reliability.</figcaption>
                        <p class="card-note">We scale with design tokens, patterns, and CI-backed QA — WCAG checks, keyboard navigation, and performance baked in.</p>
                    </figure>
                </div>
            </section>

            <!-- ===== Benefits / How we work ===== -->
            <section class="benefits" aria-labelledby="benefits-title">
                <h2 id="benefits-title">How We Build</h2>
                <ul class="benefits__grid">
                    <li class="benefit">
                        <h3>Design × Engineering</h3>
                        <p>Product designers and frontend engineers work in pairs to turn insight into consistent, reusable UI.</p>
                    </li>
                    <li class="benefit">
                        <h3>Accessible by Default</h3>
                        <p>Focus states, keyboard navigation, contrast checks — WCAG is embedded in our process, not an afterthought.</p>
                    </li>
                    <li class="benefit">
                        <h3>Systems that Scale</h3>
                        <p>Tokens, patterns, and documented components power fast, reliable delivery across products.</p>
                    </li>
                </ul>
            </section>

            <!-- ===== Split band with image and supporting copy ===== -->
            <section class="split" aria-labelledby="split-title">
                <div class="split__media">
                    <img src="images/AdobeStock_626033852.jpeg" alt="Human vs AI boxing face-off">
                </div>
                <div class="split__content">
                    <h2 id="split-title">Tech & Design in Action</h2>
                    <p>
                        We don’t just observe technology; we challenge it. From accessible UI and performance
                        optimisation to data-driven decisions, our teams deliver products that balance craft and clarity.
                    </p>
                    <ul class="checklist">
                        <li>Reusable components &amp; documented patterns</li>
                        <li>Inclusive research &amp; WCAG-minded QA flows</li>
                        <li>Performance and reliability at scale</li>
                    </ul>
                    <a class="btn" href="apply.php">Apply Now</a>
                </div>
            </section>
        </main>

        <!-- ================== FOOTER ================== -->
        <?php include_once "footer.inc"; ?>

    </body>
</html>
