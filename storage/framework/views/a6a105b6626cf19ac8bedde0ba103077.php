<?php $__env->startSection('title', 'Take Quiz - ' . $attempt->quiz->title); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .quiz-timer { font-size: 1.25rem; font-weight: 700; color: #059669; }
    .question-card { border-left: 4px solid #10b981; }
    .question-card.question-unanswered {
        border-left-color: #dc2626;
        border-color: #fca5a5;
        box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.18);
        background: #fef2f2;
    }
    .question-unanswered-flag {
        display: none;
    }
    .question-card.question-unanswered .question-unanswered-flag {
        display: inline-block;
    }
    .option-label { cursor: pointer; padding: 10px 14px; border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 8px; transition: all .2s; }
    .option-label:hover { border-color: #10b981; background: #f0fdf4; }
    .option-label input:checked + span { font-weight: 600; color: #059669; }

    .quiz-protected-content,
    .quiz-protected-content * {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-touch-callout: none;
    }

    .quiz-protected-content input[type="radio"] {
        -webkit-user-select: auto;
        user-select: auto;
    }

    .quiz-violation-toast {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        min-width: 320px;
        max-width: 420px;
        box-shadow: 0 8px 24px rgba(220, 38, 38, 0.25);
        animation: violation-shake 0.4s ease;
    }

    @keyframes violation-shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-6px); }
        75% { transform: translateX(6px); }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="bi bi-pencil-square me-2"></i><?php echo e($attempt->quiz->title); ?></h1>
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('trainee.quizzes.index')); ?>">Quizzes</a></li>
                <li class="breadcrumb-item active">Take Quiz</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3">
            <?php if($endsAt): ?>
            <div class="quiz-timer" id="timer" data-ends="<?php echo e($endsAt->timestamp); ?>">
                <i class="bi bi-clock me-1"></i><span id="timer-text">--:--</span>
            </div>
            <?php endif; ?>
            <span class="badge bg-light text-muted" id="quiz-save-status">Answers save automatically</span>
        </div>
    </div>
</div>

<div class="alert alert-info">
    <i class="bi bi-cloud-check me-2"></i>
    Your answers are saved as you select them. You can leave and continue later.
    <strong>Copying, cutting, or saving quiz text is strictly prohibited.</strong>
</div>

<?php if(session('error')): ?>
<div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div id="quiz-unanswered-alert" class="alert alert-danger d-none" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <span id="quiz-unanswered-alert-text">Please answer all remaining questions before submitting.</span>
</div>

<form method="POST" action="<?php echo e(route('trainee.quizzes.submit', $attempt)); ?>" id="quiz-form" class="quiz-protected-content">
    <?php echo csrf_field(); ?>

    <?php
        $qNum = 0;
        $currentPart = null;
        $savedAnswers = $savedAnswers ?? collect();
    ?>
    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $qNum++;
        $shuffledOptions = $attempt->getShuffledOptionsFor($question);
    ?>

    <?php if($question->part && $question->part !== $currentPart): ?>
    <?php $currentPart = $question->part; ?>
    <div class="alert alert-light border mt-4 mb-3"><strong><?php echo e($currentPart); ?></strong></div>
    <?php endif; ?>

    <div class="card question-card mb-3" data-question-id="<?php echo e($question->id); ?>" id="question-<?php echo e($question->id); ?>">
        <div class="card-body">
            <p class="fw-semibold mb-3">
                Question <?php echo e($qNum); ?>

                <span class="badge bg-light text-dark"><?php echo e($question->marks); ?> mark(s)</span>
                <span class="badge bg-danger question-unanswered-flag">Unanswered</span>
            </p>
            <p class="mb-3"><?php echo e($question->question_text); ?></p>

            <?php $__currentLoopData = $shuffledOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optIndex => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="option-label d-flex align-items-start gap-2">
                <input type="radio"
                       name="answers[<?php echo e($question->id); ?>]"
                       value="<?php echo e($option->id); ?>"
                       class="mt-1 quiz-answer"
                       data-question-id="<?php echo e($question->id); ?>"
                       <?php echo e((string) ($savedAnswers[$question->id] ?? '') === (string) $option->id ? 'checked' : ''); ?>>
                <span><?php echo e($option->option_text); ?></span>
            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="d-flex justify-content-between mt-4 mb-5">
        <button type="button" class="btn btn-outline-secondary" id="quiz-save-exit">
            <i class="bi bi-box-arrow-left me-1"></i>Save &amp; Exit
        </button>
        <button type="submit" class="btn btn-primary btn-lg" id="quiz-submit-btn">
            <i class="bi bi-check-circle me-1"></i>Submit Quiz
        </button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const form = document.getElementById('quiz-form');
    const saveUrl = <?php echo json_encode(route('trainee.quizzes.save', $attempt), 512) ?>;
    const indexUrl = <?php echo json_encode(route('trainee.quizzes.index'), 15, 512) ?>;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const statusEl = document.getElementById('quiz-save-status');
    let saving = false;

    function collectAnswers() {
        const answers = {};
        form.querySelectorAll('input.quiz-answer:checked').forEach(function (input) {
            answers[input.dataset.questionId] = input.value;
        });
        return answers;
    }

    function setStatus(text, isError) {
        if (!statusEl) {
            return;
        }
        statusEl.textContent = text;
        statusEl.classList.toggle('text-danger', !!isError);
        statusEl.classList.toggle('text-success', !isError);
        statusEl.classList.toggle('text-muted', false);
    }

    function saveAnswers(payload, useBeacon) {
        if (useBeacon && navigator.sendBeacon) {
            const body = new FormData();
            body.append('_token', csrf);
            Object.keys(payload.answers || {}).forEach(function (questionId) {
                body.append('answers[' + questionId + ']', payload.answers[questionId]);
            });
            if (payload.question_id && payload.option_id) {
                body.append('question_id', payload.question_id);
                body.append('option_id', payload.option_id);
            }
            return navigator.sendBeacon(saveUrl, body);
        }

        saving = true;
        return fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            keepalive: true,
            body: JSON.stringify(payload)
        }).then(function (response) {
            return response.json().then(function (data) {
                if (!response.ok || !data.ok) {
                    throw new Error(data.message || 'Could not save answers');
                }
                setStatus('Saved at ' + (data.saved_at || ''));
                return data;
            });
        }).catch(function () {
            setStatus('Could not save. Check your connection.', true);
            return Promise.reject();
        }).finally(function () {
            saving = false;
        });
    }

    form.addEventListener('change', function (event) {
        if (!event.target.classList.contains('quiz-answer')) {
            return;
        }
        const card = event.target.closest('.question-card');
        if (card) {
            card.classList.remove('question-unanswered');
        }
        setStatus('Saving...');
        saveAnswers({
            question_id: event.target.dataset.questionId,
            option_id: event.target.value,
            answers: collectAnswers()
        });
    });

    function unansweredCards() {
        return Array.from(form.querySelectorAll('.question-card')).filter(function (card) {
            return !card.querySelector('input.quiz-answer:checked');
        });
    }

    function highlightUnanswered(cards) {
        form.querySelectorAll('.question-card').forEach(function (card) {
            card.classList.remove('question-unanswered');
        });
        cards.forEach(function (card) {
            card.classList.add('question-unanswered');
        });

        const alertBox = document.getElementById('quiz-unanswered-alert');
        const alertText = document.getElementById('quiz-unanswered-alert-text');
        if (alertBox && alertText) {
            alertText.textContent = cards.length === 1
                ? '1 question is still unanswered. Please select an option before submitting.'
                : cards.length + ' questions are still unanswered. Please complete the highlighted questions before submitting.';
            alertBox.classList.remove('d-none');
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        if (cards[0]) {
            window.setTimeout(function () {
                cards[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 250);
        }
    }

    form.addEventListener('submit', function (event) {
        const remaining = unansweredCards();
        if (remaining.length) {
            event.preventDefault();
            highlightUnanswered(remaining);
            return false;
        }

        const alertBox = document.getElementById('quiz-unanswered-alert');
        if (alertBox) {
            alertBox.classList.add('d-none');
        }

        if (!confirm('Submit your quiz? You cannot change answers after submission.')) {
            event.preventDefault();
            return false;
        }
    });

    function saveAllOnExit() {
        const answers = collectAnswers();
        if (Object.keys(answers).length === 0) {
            return;
        }
        saveAnswers({ answers: answers }, true);
    }

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            saveAllOnExit();
        }
    });

    window.addEventListener('pagehide', saveAllOnExit);

    document.getElementById('quiz-save-exit')?.addEventListener('click', function () {
        setStatus('Saving...');
        saveAnswers({ answers: collectAnswers() }).then(function () {
            window.location.href = indexUrl;
        }).catch(function () {});
    });
})();
</script>
<script>
(function () {
    const protectedRoot = document.getElementById('quiz-form');
    const violationContainer = document.getElementById('quiz-violation-container');

    if (!protectedRoot || !violationContainer) {
        return;
    }

    let violationCount = 0;
    let lastAlertAt = 0;

    const blockedShortcuts = {
        'c': 'Copy',
        'x': 'Cut',
        'a': 'Select all',
        'u': 'View source',
        's': 'Save page',
        'p': 'Print',
        'v': 'Paste',
    };

    function showViolation(message) {
        violationCount++;
        const now = Date.now();

        if (now - lastAlertAt < 800) {
            return;
        }

        lastAlertAt = now;

        const toast = document.createElement('div');
        toast.className = 'alert alert-danger quiz-violation-toast mb-2';
        toast.setAttribute('role', 'alert');
        toast.innerHTML =
            '<div class="d-flex align-items-start gap-2">' +
                '<i class="bi bi-exclamation-octagon-fill fs-5 flex-shrink-0"></i>' +
                '<div>' +
                    '<strong>Security Violation #' + violationCount + '</strong><br>' +
                    '<span>' + message + '</span><br>' +
                    '<small class="text-muted">Copying quiz content is not allowed. Repeated violations may be reported.</small>' +
                '</div>' +
                '<button type="button" class="btn-close ms-auto" aria-label="Close"></button>' +
            '</div>';

        toast.querySelector('.btn-close').addEventListener('click', function () {
            toast.remove();
        });

        violationContainer.prepend(toast);

        window.setTimeout(function () {
            toast.classList.add('fade');
            toast.style.opacity = '0';
            window.setTimeout(function () {
                toast.remove();
            }, 300);
        }, 5000);
    }

    function isInsideQuiz(target) {
        return target && protectedRoot.contains(target);
    }

    function blockEvent(event, message) {
        event.preventDefault();
        event.stopPropagation();
        showViolation(message);
        return false;
    }

    protectedRoot.addEventListener('copy', function (event) {
        blockEvent(event, 'Copy action blocked.');
    });

    protectedRoot.addEventListener('cut', function (event) {
        blockEvent(event, 'Cut action blocked.');
    });

    protectedRoot.addEventListener('paste', function (event) {
        blockEvent(event, 'Paste action blocked.');
    });

    protectedRoot.addEventListener('contextmenu', function (event) {
        blockEvent(event, 'Right-click is disabled during the quiz.');
    });

    protectedRoot.addEventListener('selectstart', function (event) {
        if (event.target && event.target.tagName === 'INPUT') {
            return;
        }
        blockEvent(event, 'Text selection is not allowed.');
    });

    protectedRoot.addEventListener('dragstart', function (event) {
        blockEvent(event, 'Dragging quiz content is not allowed.');
    });

    document.addEventListener('keydown', function (event) {
        if (!isInsideQuiz(event.target) && !protectedRoot.contains(document.activeElement)) {
            return;
        }

        const key = event.key.toLowerCase();
        const ctrlOrMeta = event.ctrlKey || event.metaKey;

        if (ctrlOrMeta && blockedShortcuts[key]) {
            blockEvent(event, blockedShortcuts[key] + ' shortcut blocked.');
            return;
        }

        if (event.key === 'F12') {
            blockEvent(event, 'Developer tools shortcut blocked.');
            return;
        }

        if (ctrlOrMeta && event.shiftKey && ['i', 'j', 'c'].includes(key)) {
            blockEvent(event, 'Developer tools shortcut blocked.');
        }
    });

    document.addEventListener('copy', function (event) {
        const selection = window.getSelection();
        if (!selection || selection.isCollapsed) {
            return;
        }

        const anchor = selection.anchorNode;
        if (anchor && protectedRoot.contains(anchor.nodeType === 3 ? anchor.parentNode : anchor)) {
            blockEvent(event, 'Copy action blocked.');
        }
    });
})();
</script>
<?php if($endsAt): ?>
<script>
(function () {
    const el = document.getElementById('timer-text');
    const ends = parseInt(document.getElementById('timer').dataset.ends, 10) * 1000;
    const form = document.getElementById('quiz-form');

    function tick() {
        const diff = ends - Date.now();
        if (diff <= 0) {
            el.textContent = '00:00';
            form.submit();
            return;
        }
        const m = Math.floor(diff / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        el.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/trainee/quizzes/take.blade.php ENDPATH**/ ?>