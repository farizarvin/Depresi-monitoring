@extends('layouts.focused')

@section('title', 'DASS-21 Questionnaire')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-primary text-white p-3 p-md-4 border-0">
                    <h4 class="mb-0 fw-bold fs-5 fs-md-4"><i class="bi bi-clipboard-data me-2"></i> Kuesioner DASS-21</h4>
                </div>
                <div class="card-body p-3 p-md-5">
                    
                    {{-- Instructions --}}
                    <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info mb-4" role="alert" style="border-radius: 15px;">
                        <h5 class="alert-heading fw-bold fs-6"><i class="bi bi-info-circle-fill me-2"></i> Petunjuk Pengisian</h5>
                        <p class="mb-0 small">
                            Di bawah ini terdapat 21 pernyataan yang berisi tentang kondisi-kondisi tertentu. Silakan pilih salah satu tombol jawaban yang paling sesuai dengan kondisi Anda dalam <strong>satu minggu terakhir</strong>.
                        </p>
                    </div>

                    {{-- Question Container --}}
                    <div id="questionContainer">
                        {{-- Progress Bar --}}
                        <div class="progress mb-4" style="height: 10px; border-radius: 5px;">
                            <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
                        </div>

                        {{-- Question Text --}}
                        <div class="text-center mb-4 mb-md-5">
                            <span class="badge bg-light text-primary mb-3 px-3 py-2 rounded-pill border">Pertanyaan <span id="currentNumber">1</span> dari 21</span>
                            <h3 class="fw-bold text-dark fs-4 fs-md-3" id="questionText">Loading...</h3>
                        </div>

                        {{-- Options Container --}}
                        <div class="d-grid gap-2 gap-md-3 mb-4 mb-md-5" id="optionsContainer">
                            {{-- Options will be injected here by JS --}}
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="d-flex justify-content-between gap-2">
                            <button id="prevBtn" class="btn btn-outline-secondary px-3 px-md-4 rounded-pill flex-fill flex-md-grow-0" onclick="handlePrev()" style="visibility: hidden;">
                                <i class="bi bi-arrow-left me-1"></i> Prev
                            </button>
                            <button id="nextBtn" class="btn btn-primary px-3 px-md-4 rounded-pill flex-fill flex-md-grow-0" onclick="handleNext()" disabled>
                                Next <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Completion Message (Hidden initially) --}}
                    <div id="completionMessage" class="text-center" style="display: none;">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Terima Kasih!</h3>
                        <p class="text-muted mb-4">Anda telah menyelesaikan kuesioner ini.</p>
                        <a href="{{ route('siswa.diaryku') }}" class="btn btn-primary px-5 rounded-pill">Lanjut ke Dashboard Diaryku</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Form to Submit Data --}}
<form id="dassForm" method="POST" action="#" style="display: none;">
    @csrf
    <input type="hidden" name="answers" id="answersInput">
</form>

<script>
    const questions = [
        "Saya merasa sulit untuk beristirahat",
        "Saya merasa bibir saya sering kering",
        "Saya sama sekali tidak dapat merasakan perasaan positif",
        "Saya mengalami kesulitan bernafas (misalnya: seringkali terengah-engah atau tidak dapat bernafas padahal tidak melakukan aktivitas fisik sebelumnya)",
        "Saya merasa sulit untuk meningkatkan insiatif dalam melakukan sesuatu",
        "Saya cenderung bereaksi berlebihan terhadap suatu situasi.",
        "Saya merasa gemetar (misalnya: pada tangan)",
        "Saya merasa telah menghabiskan banyak energi untuk merasa cemas.",
        "Saya merasa khawatir dengan situasi dimana saya mungkin menjadi panik dan mempermalukan diri sendiri.",
        "Saya merasa tidak ada hal yang dapat diharapkan di masa depan",
        "Saya melihat bahwa diri saya mudah gelisah.",
        "Saya merasa sulit untuk bersantai/relaks",
        "Saya merasa putus asa dan sedih.",
        "Saya tidak dapat memaklumi hal apapun yang menghalangi saya untuk menyelesaikan hal yang sedang saya lakukan.",
        "Saya merasa saya mudah panik.",
        "Saya tidak merasa antusias dalam hal apapun.",
        "Saya merasa bahwa saya tidak berharga sebagai seorang manusia.",
        "Saya merasa bahwa saya mudah tersinggung.",
        "Saya menyadari aktivitas jantung saya, walaupun saya tidak sehabis melakukan aktivitas fisik (misalnya: merasa detak jantung meningkat atau melemah).",
        "Saya merasa takut tanpa alasan yang jelas.",
        "Saya merasa bahwa hidup tidak berarti."
    ];

    const options = [
        "Tidak Pernah",
        "Kadang-Kadang",
        "Sering",
        "Hampir Selalu"
    ];

    let currentQuestionIndex = 0;
    // Initialize answers array with null values
    let answers = new Array(questions.length).fill(null);

    const questionTextEl = document.getElementById('questionText');
    const currentNumberEl = document.getElementById('currentNumber');
    const progressBarEl = document.getElementById('progressBar');
    const questionContainerEl = document.getElementById('questionContainer');
    const optionsContainerEl = document.getElementById('optionsContainer');
    const completionMessageEl = document.getElementById('completionMessage');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    function loadQuestion() {
        if (currentQuestionIndex < questions.length) {
            // Update Text
            questionTextEl.textContent = questions[currentQuestionIndex];
            currentNumberEl.textContent = currentQuestionIndex + 1;
            
            // Update Progress
            const progress = ((currentQuestionIndex) / questions.length) * 100;
            progressBarEl.style.width = `${progress}%`;

            // Render Options
            renderOptions();
            
            // Update Navigation Buttons
            updateNavigation();
        } else {
            showCompletion();
        }
    }

    function renderOptions() {
        optionsContainerEl.innerHTML = ''; // Clear previous options
        
        const currentAnswer = answers[currentQuestionIndex];
        
        options.forEach((text, index) => {
            const btn = document.createElement('button');
            const isSelected = currentAnswer !== null && currentAnswer.value === index;

            
            
            // Base class
            let btnClass = 'btn p-3 text-start option-btn ';
            // Dynamic class based on selection
            btnClass += isSelected ? 'btn-primary text-white' : 'btn-outline-secondary';
            
            btn.className = btnClass;
            btn.onclick = () => selectOption(index);
            
            const checkHidden = isSelected ? '' : 'd-none';
            
            btn.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="rounded-circle border border-2 me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; border-color: ${isSelected ? 'white' : 'inherit'} !important;">
                        <div class="rounded-circle bg-white ${checkHidden} check-indicator" style="width: 12px; height: 12px;"></div>
                    </div>
                    <span class="fw-medium">${text}</span>
                </div>
            `;
            
            optionsContainerEl.appendChild(btn);
        });

        
        
    }

    function selectOption(value) {
        // Save answer
        answers[currentQuestionIndex] = {
            question_index: currentQuestionIndex,
            value: value
        };

        // Re-render to show selection
        renderOptions();
        
        // Enable Next button
        nextBtn.disabled = false;
    }
    
    function updateNavigation() {
        // Prev Button
        prevBtn.style.visibility = currentQuestionIndex === 0 ? 'hidden' : 'visible';
        
        // Next Button Text & State
        const isLastQuestion = currentQuestionIndex === questions.length - 1;
        if (isLastQuestion) {
            nextBtn.innerHTML = 'Kirim <i class="bi bi-send-fill ms-1"></i>';
            nextBtn.classList.remove('btn-primary');
            nextBtn.classList.add('btn-success');
        } else {
            nextBtn.innerHTML = 'Lanjut <i class="bi bi-arrow-right ms-1"></i>';
            nextBtn.classList.add('btn-primary');
            nextBtn.classList.remove('btn-success');
        }
        
        // Disable next if no answer selected yet
        optionsContainerEl.querySelectorAll('button')[3].click();
        nextBtn.disabled = answers[currentQuestionIndex] === null;

        // Auto Click
        handleNext();
    }

    function handlePrev() {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            loadQuestion();
        }
    }
    
    function handleNext() {
        const isLastQuestion = currentQuestionIndex === questions.length - 1;
        
        if (isLastQuestion) {
            showCompletion();
        } else {
            currentQuestionIndex++;
            loadQuestion();
        }
    }

    async function showCompletion() {
        // Prepare data - filter out any potential nulls (though logic should prevent valid nulls on submit)
        const validAnswers = answers.filter(a => a !== null);
        
        // UI Loading State
        nextBtn.disabled = true;
        nextBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengirim...';
        
        // Prepare data
        document.getElementById('answersInput').value = JSON.stringify(validAnswers);

        // Submit via AJAX
        try {
            const response = await fetch("{{ route('dass21.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ answers: JSON.stringify(validAnswers) })
            });
            
            const data = await response.json();
            console.log(data);

            if (response.ok) {
                // Show completion message only after success
                questionContainerEl.style.display = 'none';
                completionMessageEl.style.display = 'block';
            } else {
                alert('Gagal menyimpan: ' + (data.message || 'Unknown error'));
                nextBtn.disabled = false;
                nextBtn.innerHTML = 'Kirim <i class="bi bi-send-fill ms-1"></i>';
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan koneksi.');
            nextBtn.disabled = false;
            nextBtn.innerHTML = 'Kirim <i class="bi bi-send-fill ms-1"></i>';
        }
    }

    // Initialize
    loadQuestion();
</script>
@endsection
```
