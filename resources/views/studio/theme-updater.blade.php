@if(auth()->user()->role == 'admin')
@if(env('ENABLE_THEME_UPDATER') == 'true')

<br><br><br>
<div class="accordion">
    <div class="accordion-item">
        <h2 class="accordion-header" id="details-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-collapse" aria-controls="details-collapse">
                {{ __('messages.Theme Updater') }}
            </button>
        </h2>
        <div id="details-collapse" class="accordion-collapse collapse" aria-labelledby="details-header">
            <div class="accordion-body table-responsive">
                <table class="table table-striped table-responsive">
                    <tr>
                        <th>{{ __('messages.Theme name') }}</th>
                        <th>{{ __('messages.Update status') }}</th>
                        <th>{{ __('messages.Version') }}&nbsp;</th>
                    </tr>
                    @php
                        if ($handle = opendir(base_path('themes'))) {
                            while (false !== ($entry = readdir($handle))) {
                                if ($entry != "." && $entry != "..") {
                                    $verNr = 'unknown';
                                    $themeVe = null;
                                    $hasSource = false;
                                    $updateAv = false;

                                    $readmePath = base_path("themes/{$entry}/readme.md");
                                    if (file_exists($readmePath)) {
                                        $text = file_get_contents($readmePath);
                                        if (preg_match('/Theme Version:.*/', $text, $matches)) {
                                            $verNr = isset($matches[0]) ? substr($matches[0], 15) : 'unknown';
                                        }
                                        $hasSource = strpos($text, 'Source code:') !== false;
                                    }

                                    if ($hasSource) {
                                        $sourceURL = $matches[0] ?? '';
                                        $replaced = str_replace("https://github.com/", "https://raw.githubusercontent.com/", trim($sourceURL));
                                        $replaced .= "/main/readme.md";

                                        if (strpos($sourceURL, 'github.com')) {
                                            ini_set('user_agent', 'Mozilla/4.0 (compatible; MSIE 6.0)');
                                            try {
                                                $textGit = external_file_get_contents($replaced);
                                                if (preg_match('/Theme Version:.*/', $textGit, $matches)) {
                                                    $sourceURLGit = substr($matches[0], 15);
                                                    $Vgitt = 'v' . ($sourceURLGit ?? '');
                                                    $verNrv = 'v' . ($verNr ?? '');

                                                    $updateAv = trim($Vgitt ?? '') > trim($verNrv ?? '');
                                                    $GLOBALS['updateAv'] = $updateAv || $GLOBALS['updateAv'];
                                                }
                                            } catch (Exception $ex) {
                                                error_log('Error fetching GitHub theme version: ' . $ex->getMessage());
                                                $themeVe = "error";
                                            }
                                        } else {
                                            $themeVe = "error";
                                        }
                                    }

                                    echo '<tr>';
                                    echo '<th>' . ucfirst($entry) . '</th>';
                                    echo '<th><center>';
                                    if ($themeVe === "error") {
                                        echo '<span class="badge bg-danger">' . __('messages.Error') . '</span>';
                                    } elseif (!$hasSource) {
                                        echo '<a href="https://linkstack.org/themes.php" target="_blank"><span class="badge bg-danger">' . __('messages.Update manually') . '</span></a>';
                                    } elseif ($updateAv) {
                                        echo '<span class="badge bg-warning">' . __('messages.Update available') . '</span>';
                                    } else {
                                        echo '<span class="badge bg-success">' . __('messages.Up to date') . '</span>';
                                    }
                                    echo '</center></th>';
                                    echo '<th>' . $verNr . '</th>';
                                    echo '</tr>';
                                }
                            }
                            closedir($handle);
                        }
                    @endphp
                </table>
            </div>
            <a href="{{ url('update/theme') }}" onclick="updateicon()" class="btn btn-gray ms-3 mb-4">
                <span id="updateicon"><i class="bi bi-arrow-repeat"></i></span> {{ __('messages.Update all themes') }}
            </a>
        </div>
    </div>
</div>


<?php
try{ if($GLOBALS['updateAv'] == true) echo '<p class="mt-3 ml-3 h2""><span class="badge bg-success">'.__('messages.Update available').'</span></p>';
}catch(Exception $ex){}
?>

<script>
    $(function() {
        $('select[name=theme]').on('change', function() {
            var s = $(this).data('base-url') + "?t=" + $(this).val();
            $("#frPreview").prop('src', s);
        })
    });

    class Accordion {
        constructor(el) {
            // Store the <details> element
            this.el = el;
            // Store the <summary> element
            this.summary = el.querySelector('summary');
            // Store the <div class="content"> element
            this.content = el.querySelector('.content');

            // Store the animation object (so we can cancel it if needed)
            this.animation = null;
            // Store if the element is closing
            this.isClosing = false;
            // Store if the element is expanding
            this.isExpanding = false;
            // Detect user clicks on the summary element
            this.summary.addEventListener('click', (e) => this.onClick(e));
        }

        onClick(e) {
            // Stop default behaviour from the browser
            e.preventDefault();
            // Add an overflow on the <details> to avoid content overflowing
            this.el.style.overflow = 'hidden';
            // Check if the element is being closed or is already closed
            if (this.isClosing || !this.el.open) {
                this.open();
                // Check if the element is being openned or is already open
            } else if (this.isExpanding || this.el.open) {
                this.shrink();
            }
        }

        shrink() {
            // Set the element as "being closed"
            this.isClosing = true;

            // Store the current height of the element
            const startHeight = `${this.el.offsetHeight}px`;
            // Calculate the height of the summary
            const endHeight = `${this.summary.offsetHeight}px`;

            // If there is already an animation running
            if (this.animation) {
                // Cancel the current animation
                this.animation.cancel();
            }

            // Start a WAAPI animation
            this.animation = this.el.animate({
                // Set the keyframes from the startHeight to endHeight
                height: [startHeight, endHeight]
            }, {
                duration: 400
                , easing: 'ease-out'
            });

            // When the animation is complete, call onAnimationFinish()
            this.animation.onfinish = () => this.onAnimationFinish(false);
            // If the animation is cancelled, isClosing variable is set to false
            this.animation.oncancel = () => this.isClosing = false;
        }

        open() {
            // Apply a fixed height on the element
            this.el.style.height = `${this.el.offsetHeight}px`;
            // Force the [open] attribute on the details element
            this.el.open = true;
            // Wait for the next frame to call the expand function
            window.requestAnimationFrame(() => this.expand());
        }

        expand() {
            // Set the element as "being expanding"
            this.isExpanding = true;
            // Get the current fixed height of the element
            const startHeight = `${this.el.offsetHeight}px`;
            // Calculate the open height of the element (summary height + content height)
            const endHeight = `${this.summary.offsetHeight + this.content.offsetHeight}px`;

            // If there is already an animation running
            if (this.animation) {
                // Cancel the current animation
                this.animation.cancel();
            }

            // Start a WAAPI animation
            this.animation = this.el.animate({
                // Set the keyframes from the startHeight to endHeight
                height: [startHeight, endHeight]
            }, {
                duration: 400
                , easing: 'ease-out'
            });
            // When the animation is complete, call onAnimationFinish()
            this.animation.onfinish = () => this.onAnimationFinish(true);
            // If the animation is cancelled, isExpanding variable is set to false
            this.animation.oncancel = () => this.isExpanding = false;
        }

        onAnimationFinish(open) {
            // Set the open attribute based on the parameter
            this.el.open = open;
            // Clear the stored animation
            this.animation = null;
            // Reset isClosing & isExpanding
            this.isClosing = false;
            this.isExpanding = false;
            // Remove the overflow hidden and the fixed height
            this.el.style.height = this.el.style.overflow = '';
        }
    }

    document.querySelectorAll('details').forEach((el) => {
        new Accordion(el);
    });

</script>

@endif
@endif
