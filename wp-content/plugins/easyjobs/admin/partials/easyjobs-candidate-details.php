<?php
/**
 * Candidate details page for easyjobs
 *
 * @link       https://easy.jobs
 * @since      1.0.0
 *
 * @package    Easyjobs
 * @subpackage Easyjobs/admin/partials
 */

?>
<div class="wrap">
	<hr class="wp-header-end">
	<?php if ( ! empty( $data ) ) : ?>
		<div class="easy-page-body">
			<input type="hidden" name="job_id" class="ej-job-id"
				   value="<?php echo esc_html( $data->candidate->job_id ); ?>">
			<main class="content-area">
				<?php include EASYJOBS_ADMIN_DIR_PATH . '/partials/easyjobs-admin-header.php'; ?>
				<!-- content body -->
				<div class="content-area__body section-gap">
					<!-- applicant details -->
					<section class="applicant-details">
						<div class="d-flex justify-content-between align-items-center">
							<div class="back-button mt-0">
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-candidates' ) ); ?>"
								   class="back-button__icon">
									<i class="easyjobs-icon easyjobs-back"></i>
								</a>
								<div class="back-button__text">Back</div>
							</div>
							<button class="button danger-button ej-remove-candidate">Remove</button>
						</div>

						<div class="row">
							<div class="col-xl-3 d-flex flex-column pr-0">
								<div
									class="user-card gutter-10 h-auto candidate-details-user <?php echo $data->evaluation->showAiScore ? esc_html( 'has-ai-score' ) : ''; ?>">
									<div class="user-picture">
										<?php if ( $ai_enabled && $data->evaluation->showAiScore ) : ?>
											<?php Easyjobs_Helper::get_ai_score_chart( $data->evaluation->scores ); ?>
										<?php endif; ?>
										<img src="<?php echo esc_url( $data->candidate->user->profile_image ); ?>"
											 alt="applicant-img" class="img-fluid"/>
									</div>
									<div class="user-info">
										<h5> <?php echo ! empty( $data->candidate->user->name ) ? esc_html( $data->candidate->user->name ) : ''; ?> </h5>
										<p> <?php echo esc_html( $data->candidate->job_title ); ?> </p>
									</div>
									<div class="dropdown pipeline-action selected candidate-details-pipeline">
										<button class="button primary-button pipeline-toggle" data-toggle="dropdown"
												aria-haspopup="true" aria-expanded="false">
											<?php echo ! empty( $data->candidate->pipeline ) && ! empty( $data->candidate->pipeline->name ) ? esc_html( $data->candidate->pipeline->name ) : ''; ?>
										</button>
										<div class="dropdown-menu">
											<?php foreach ( $data->candidate->job_pipelines as $pipeline ) : ?>
												<a class="dropdown-item stage" href="#"
												   data-stage="<?php echo esc_html( $pipeline->id ); ?>"
												   data-job-id="<?php echo esc_html( $data->candidate->job_id ); ?>"
												   data-candidate-id="<?php echo esc_html( $data->candidate->id ); ?>"><?php echo esc_html( $pipeline->name ); ?></a>
											<?php endforeach; ?>
										</div>
									</div>
									<div
										class="user__ratting user__ratting--removable info-button-light <?php echo intval( $data->candidate->rating ) === 0 ? esc_html( 'disabled' ) : ''; ?>">
										<input type="hidden" value="<?php echo esc_html( $data->candidate->rating ); ?>"
											   class="candidate-rating">
										<i class="easyjobs-icon easyjobs-star"></i> <?php echo esc_html( $data->candidate->rating ); ?>
									</div>
									<?php if ( $ai_enabled && $data->evaluation->showAiScore && ! empty( $data->evaluation->scores ) ) : ?>
										<?php Easyjobs_Helper::get_ai_score_details( $data->evaluation->scores ); ?>
									<?php endif; ?>
								</div>
								<div class="user-card align-items-baseline gutter-10">
									<ul class="user-info user-info__list">
										<li>
											<div class="user-icon">
												<i class="easyjobs-icon easyjobs-user"></i>
											</div>
											<div class="user-text">
												<p class="user-text__label"><?php esc_html_e( 'First Name', 'easyjobs' ); ?>
													*</p>
												<p><?php echo esc_html( $data->candidate->user->first_name ); ?></p>
											</div>
										</li>
										<li>
											<div class="user-icon">
												<i class="easyjobs-icon easyjobs-user"></i>
											</div>
											<div class="user-text">
												<p class="user-text__label"><?php esc_html_e( 'Last Name', 'easyjobs' ); ?>
													*</p>
												<p><?php echo esc_html( $data->candidate->user->last_name ); ?></p>
											</div>
										</li>
										<li>
											<div class="user-icon">
												<i class="easyjobs-icon easyjobs-mail"></i>
											</div>
											<div class="user-text">
												<p class="user-text__label"><?php esc_html_e( 'Email Address', 'easyjobs' ); ?>
													*</p>
												<p class="user-email"><?php echo esc_html( $data->candidate->user->email ); ?></p>
											</div>
										</li>
										<li>
											<div class="user-icon">
												<i class="easyjobs-icon easyjobs-phone"></i>
											</div>
											<div class="user-text">
												<p class="user-text__label"><?php esc_html_e( 'Phone Number', 'easyjobs' ); ?>
													*</p>
												<p><?php echo esc_html( $data->candidate->user->mobile_number ); ?></p>
											</div>
										</li>
										<li>
											<div class="user-icon">
												<i class="easyjobs-icon easyjobs-calender"></i>
											</div>
											<div class="user-text">
												<p class="user-text__label"><?php esc_html_e( 'Date of Application', 'easyjobs' ); ?></p>
												<p><?php echo esc_html( $data->candidate->created_at ); ?></p>
											</div>
										</li>
										<li>
											<div class="user-icon">
												<i class="easyjobs-icon easyjobs-star"></i>
											</div>
											<div class="user-text">
												<p class="user-text__label"><?php esc_html_e( 'Rate', 'easyjobs' ); ?>
													*</p>
												<div class="user__text__ratting">
													<?php Easyjobs_Helper::rating_icon( $data->candidate->rating ); ?>
												</div>
											</div>
										</li>
										<li>
											<div class="user-icon">
												<i class="easyjobs-icon easyjobs-share"></i>
											</div>
											<div class="user-text">
												<p class="user-text__label"><?php esc_html_e( 'Social Profile', 'easyjobs' ); ?>
													*</p>
												<div class="mt-2">
													<?php foreach ( $data->candidate->user->social_profiles as $profile ) : ?>
														<a class="social-button semi-button-primary"
														   href="<?php echo esc_url( $profile->link ); ?>"
														   target="_blank">
															<?php
															echo wp_kses(
																Easyjobs_Helper::get_social_link_icon( $profile->type ),
																array(
																	'i' => array(
																		'class' => array(),
																	),
																)
															);
															?>
														</a>
													<?php endforeach; ?>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<div class="col-xl-6 pr-0">
								<div class="tab__card tab__card--primary gutter-10 nav-tabs candidate-tabs">
									<a class="tab__control active nav-link" data-toggle="tab" href="#application"
									   role="tab" aria-controls="application" aria-selected="true">
										<div class="tab__control__icon"><i class="easyjobs-icon easyjobs-paper"></i>
										</div>
										<div
											class="tab__control__text"><?php esc_html_e( 'Application', 'easyjobs' ); ?></div>
									</a>
									<a class="tab__control nav-link" data-toggle="tab" href="#resume" role="tab"
									   aria-controls="resume" aria-selected="false">
										<div class="tab__control__icon"><i class="easyjobs-icon easyjobs-cv"></i></div>
										<div class="tab__control__text"><?php esc_html_e( 'Resume', 'easyjobs' ); ?></div>
									</a>
									<a class="tab__control nav-link" data-toggle="tab" href="#evaluation" role="tab"
									   aria-controls="evaluation" aria-selected="false">
										<div class="tab__control__icon"><i class="easyjobs-icon easyjobs-contract"></i>
										</div>
										<div
											class="tab__control__text"><?php esc_html_e( 'Evaluation', 'easyjobs' ); ?></div>
									</a>
								</div>
								<div class="tab-content">
									<div class="tab-pane fade show active" id="application" role="tabpanel"
										 aria-labelledby="application">
										<div class="details__card gutter-10">
											<div class="details__card__head">
												<h4><?php esc_html_e( 'Cover Letter', 'easyjobs' ); ?></h4>
											</div>
											<div class="details__card__body">
												<?php if ( ! empty( $data->candidate->cover_letter ) ) : ?>
													<div class="details__text__pre">
														<?php echo wp_kses(
                                                                $data->candidate->cover_letter,
                                                                array(
                                                                    'div'    => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
                                                                        'data' => array(),
                                                                    ),
                                                                    'p'      => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
																		'data' => array(),
                                                                    ),
                                                                    'h1'     => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
																		'data' => array(),
                                                                    ),
                                                                    'h2'     => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
																		'data' => array(),
                                                                    ),
                                                                    'h3'     => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
                                                                    ),
                                                                    'h4'     => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
                                                                    ),
                                                                    'span'   => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
                                                                    ),
                                                                    'strong' => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
                                                                    ),
                                                                    'em'     => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
                                                                    ),
                                                                    'b'      => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
                                                                    ),
                                                                    'a'      => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
                                                                        'href'  => array(),
                                                                        'title' => array(),
																		'data' => array(),
                                                                    ),
                                                                    'ul'  => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
                                                                    ),
                                                                    'li'  => array(
                                                                        'class' => array(),
                                                                        'style' => array(),
																		'data' => array(),
                                                                    ),
                                                                )
                                                            ); ?>
													</div>
												<?php else : ?>
													<div class="label__full  label__full--primary list-item--primary">
														<?php esc_html_e( 'No cover letter', 'easyjobs' ); ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<div class="details__card gutter-10">
											<div class="details__card__head">
												<h4><?php esc_html_e( 'EXPERIENCE', 'easyjobs' ); ?></h4>
												<p><?php esc_html_e( 'Total Year Of Experience: ', 'easyjobs' ); ?> <span>( <?php echo esc_html( $data->candidate->user->experience ); ?> <?php esc_html_e( ' Years', 'easyjobs' ); ?> )</span>
												</p>
											</div>
											<div class="details__card__body">
												<ul class="info__list">
													<?php if ( ! empty( $data->candidate->user->employments ) ) : ?>
														<?php foreach ( $data->candidate->user->employments as $employment ) : ?>
															<li class="label__full  label__full--primary list-item--primary">
																<p><?php echo esc_html( $employment->designation ); ?>
																	( <?php echo esc_html( $employment->from ) . ' - ' . esc_html( $employment->to ); ?>
																	)</p>
																<p class="label__content"><?php echo esc_html( $employment->company_name ); ?></p>
															</li>
														<?php endforeach; ?>
													<?php else : ?>
														<li class="label__full  label__full--primary list-item--primary">
															<?php esc_html_e( 'No job experience', 'easyjobs' ); ?>
														</li>
													<?php endif; ?>
												</ul>
											</div>
										</div>
										<div class="details__card gutter-10">
											<div class="details__card__head">
												<h4><?php esc_html_e( 'EDUCATIONAL QUALIFICATION', 'easyjobs' ); ?></h4>
											</div>
											<div class="details__card__body">
												<ul class="info__list">
													<?php if ( ! empty( $data->candidate->user->educations ) ) : ?>
														<?php foreach ( $data->candidate->user->educations as $education ) : ?>
															<li class="label__full  label__full--primary list-item--primary">
																<p><?php echo esc_html( $education->degree_name ); ?></p>
																<p class="text-muted"><?php echo esc_html( $education->level_name ); ?></p>
																<p class="label__content"><?php echo esc_html( $education->academy_name ); ?>
																	<span>( <?php echo esc_html( $education->passing_year ); ?> )</span>
																</p>
															</li>
														<?php endforeach; ?>
													<?php else : ?>
														<li class="label__full  label__full--primary list-item--primary">
															<?php esc_html_e( 'No educational qualification', 'easyjobs' ); ?>
														</li>
													<?php endif; ?>
												</ul>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="resume" role="tabpanel" aria-labelledby="resume">
										<div class="details__card">
											<div class="details__card__head">
												<h4><?php esc_html_e( 'Resume', 'easyjobs' ); ?></h4>
											</div>
											<div class="details__card__body">
												<div class="candidate-resume">
													<span class="resume-link"
														  data-resume-link="<?php echo esc_html( $data->candidate->user->resume_url ); ?>"></span>
													<div class="candidate-resume-iframe">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="evaluation" role="tabpanel"
										 aria-labelledby="evaluation">
										<div class="candidate-details-tab__body">
											<?php if ( $ai_enabled && $data->evaluation->showAiScore ) : ?>
												<div class="candidate-details-tab__body gutter-10">
													<div class="details__card gutter-10">
														<div class="details__card__head">
															<h4><?php esc_html_e( 'AI Score', 'easyjobs' ); ?></h4>
														</div>
														<div class="details__card__body">
															<div class="d-flex justify-content-between flex-wrap">
																<div class="text-center" style="position: relative;">
																	<p><?php esc_html_e( 'Skills', 'easyjobs' ); ?></p>
																	<div class="ai-score-chart">
																		<svg viewBox="0 0 36 36" class="circular-chart">
																			<path class="circle-bg"
																				  d="M18 2.0845
																		  a 15.9155 15.9155 0 0 1 0 31.831
																		  a 15.9155 15.9155 0 0 1 0 -31.831"
																			/>
																			<path class="circle"
																				  stroke="<?php echo esc_html( Easyjobs_Helper::get_ai_score_color( 'skill' ) ); ?>"
																				  stroke-dasharray="<?php echo esc_html( $data->evaluation->scores->skill ); ?>, 100"
																				  d="M18 2.0845
																		  a 15.9155 15.9155 0 0 1 0 31.831
																		  a 15.9155 15.9155 0 0 1 0 -31.831"
																			/>
																			<text x="18" y="20.35" class="percentage">
																				<?php echo esc_html( $data->evaluation->scores->skill ); ?>
																				%
																			</text>
																		</svg>
																	</div>
																</div>
																<div class="text-center" style="position: relative;">
																	<p>Experience</p>
																	<div class="ai-score-chart">
																		<svg viewBox="0 0 36 36" class="circular-chart">
																			<path class="circle-bg"
																				  d="M18 2.0845
																		  a 15.9155 15.9155 0 0 1 0 31.831
																		  a 15.9155 15.9155 0 0 1 0 -31.831"
																			/>
																			<path class="circle"
																				  stroke="<?php echo esc_html( Easyjobs_Helper::get_ai_score_color( 'experience' ) ); ?>"
																				  stroke-dasharray="<?php echo esc_html( $data->evaluation->scores->experience ); ?>, 100"
																				  d="M18 2.0845
																		  a 15.9155 15.9155 0 0 1 0 31.831
																		  a 15.9155 15.9155 0 0 1 0 -31.831"
																			/>
																			<text x="18" y="20.35" class="percentage">
																				<?php echo esc_html( $data->evaluation->scores->experience ); ?>
																				%
																			</text>
																		</svg>
																	</div>
																</div>
																<div class="text-center" style="position: relative;">
																	<p>Total</p>
																	<div class="ai-score-chart">
																		<svg viewBox="0 0 36 36" class="circular-chart">
																			<path class="circle-bg"
																				  d="M18 2.0845
																		  a 15.9155 15.9155 0 0 1 0 31.831
																		  a 15.9155 15.9155 0 0 1 0 -31.831"
																			/>
																			<path class="circle"
																				  stroke="<?php echo esc_html( Easyjobs_Helper::get_ai_score_color( 'final_score' ) ); ?>"
																				  stroke-dasharray="<?php echo esc_html( $data->evaluation->scores->final_score ); ?>, 100"
																				  d="M18 2.0845
																		  a 15.9155 15.9155 0 0 1 0 31.831
																		  a 15.9155 15.9155 0 0 1 0 -31.831"
																			/>
																			<text x="18" y="20.35" class="percentage">
																				<?php echo esc_html( $data->evaluation->scores->final_score ); ?>
																				%
																			</text>
																		</svg>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											<?php endif; ?>
											<?php if ( ! empty( $data->evaluation->quiz ) || ! empty( $data->evaluation->questions ) ) : ?>
												<div class="details__card gutter-10">
													<div class="details__card__head nav nav-pills" id="myTab"
														 role="tablist">
														<?php if ( ! empty( $data->evaluation->quiz ) ) : ?>
															<a class="tab--toggler active" id="quiz-tab"
															   data-toggle="tab" href="#quiz" role="tab"
															   aria-controls="quiz" aria-selected="true">
																<?php esc_html_e( 'Quiz', 'easyjobs' ); ?>
															</a>
														<?php endif; ?>
														<?php if ( ! empty( $data->evaluation->questions ) ) : ?>
															<a class="tab--toggler" id="screening-question-tab"
															   data-toggle="tab" href="#screening-question" role="tab"
															   aria-controls="screening-question" aria-selected="false">
																<?php esc_html_e( 'Screening Question', 'easyjobs' ); ?>
															</a>
														<?php endif; ?>
													</div>
													<div class="tab-content">
														<?php if ( ! empty( $data->evaluation->quiz ) ) : ?>
															<div class="tab-pane fade show active" id="quiz"
																 role="tabpanel" aria-labelledby="quiz-tab">
																<div
																	class="details__card__body d-flex justify-content-between align-items-center">
																	<ul class="info__list question-answer">
																		<li class="list-item--primary">
																			<p class="question">Total Questions:
																				<span><?php echo esc_html( $data->evaluation->quiz->count ); ?></span>
																			</p>
																		</li>
																		<li class="list-item--primary">
																			<p class="question">Total Marks:
																				<span><?php echo esc_html( $data->evaluation->quiz->totalMarks ); ?></span>
																			</p>
																		</li>
																		<li class="list-item--primary">
																			<p class="question">Marks Obtained:
																				<span><?php echo esc_html( $data->evaluation->quiz->marksObtained ); ?></span>
																			</p>
																		</li>
																	</ul>
																	<div class="text-right total-mark-obtained"
																		 style="position: relative;">
																		<svg viewBox="0 0 36 36" class="circular-chart">
																			<path class="circle-bg"
																				  d="M18 2.0845
																		  a 15.9155 15.9155 0 0 1 0 31.831
																		  a 15.9155 15.9155 0 0 1 0 -31.831"
																			/>
																			<path class="circle"
																				  stroke-dasharray="<?php echo esc_html( Easyjobs_Helper::get_mark_percentage( $data->evaluation->quiz->totalMarks, $data->evaluation->quiz->marksObtained ) ); ?>, 100"
																				  d="M18 2.0845
																		  a 15.9155 15.9155 0 0 1 0 31.831
																		  a 15.9155 15.9155 0 0 1 0 -31.831"
																			/>
																			<text x="18" y="20.35" class="percentage">
																				<?php echo esc_html( Easyjobs_Helper::get_mark_percentage( $data->evaluation->quiz->totalMarks, $data->evaluation->quiz->marksObtained ) ); ?>
																				%
																			</text>
																		</svg>
																	</div>
																</div>
																<div class="details__card__body">
																	<ul class="info__list question-answer">
																		<?php foreach ( $data->evaluation->quiz_questions as $k => $q ) : ?>
																			<li class="list-item--primary">
																				<p class="question">
																					<strong><?php esc_html_e( 'Question-' . ( $k + 1 ) . ': ', 'easyjobs' ); ?></strong><?php echo esc_html( $q->asked ); ?>
																				</p>
																				<p class="label__full--modified label__full--primary answer">
																					<span
																						class="ans-label">Ans : </span>
																					<?php echo esc_html( $q->answer ); ?>
																					<?php if ( ! empty( $q->correct_answer ) ) : ?>
																						<label class="correct-ans"><span
																								class="prefix">Correct answer: </span><?php echo esc_html( $q->correct_answer ); ?>
																						</label>
																					<?php endif; ?>
																					<?php if ( $q->is_correct ) : ?>
																						<span
																							class="result-check correct"><i
																								class="dashicons dashicons-yes"></i></span>
																					<?php else : ?>
																						<span
																							class="result-check wrong"><i
																								class="dashicons dashicons-no-alt"></i></span>
																					<?php endif; ?>
																				</p>
																			</li>
																		<?php endforeach; ?>
																	</ul>
																</div>
															</div>
														<?php endif; ?>
														<?php if ( ! empty( $data->evaluation->questions ) ) : ?>
															<div class="tab-pane fade" id="screening-question"
																 role="tabpanel"
																 aria-labelledby="screening-question-tab">

																<div class="details__card__body">
																	<ul class="info__list question-answer">
																		<?php foreach ( $data->evaluation->questions as $key => $question ) : ?>
																			<li class="list-item--primary">
																				<p class="question">
																					<strong><?php esc_html_e( 'Question-' . ( $key + 1 ) . ': ', 'easyjobs' ); ?></strong><?php echo esc_html( $question->asked ); ?>
																				</p>
																				<p class="label__full--modified label__full--primary answer">
																					<span
																						class="ans-label">Ans : </span>
																					<?php echo esc_html( $question->answer ); ?>
																					<?php if ( ! empty( $question->correct_answer ) ) : ?>
																						<label class="correct-ans">
																							<span class="prefix">Correct answer: </span><?php echo esc_html( $question->correct_answer ); ?>
																						</label>
																					<?php endif; ?>
																					<?php if ( $question->is_correct ) : ?>
																						<span
																							class="result-check correct"><i
																								class="dashicons dashicons-yes"></i></span>
																					<?php else : ?>
																						<span
																							class="result-check wrong"><i
																								class="dashicons dashicons-no-alt"></i></span>
																					<?php endif; ?>
																				</p>
																			</li>
																		<?php endforeach; ?>
																	</ul>
																</div>
															</div>
														<?php endif; ?>
													</div>
												</div>
											<?php endif; ?>
										</div>
									</div>

								</div>

							</div>
							<div class="col-xl-3 d-flex flex-column">
								<div class="details__card gutter-10">
									<div class="details__card__head">
										<h4><?php esc_html_e( 'Salary', 'easyjobs' ); ?></h4>
									</div>
									<div class="details__card__body">
										<ul class="info__list">
											<li class="label__full  label__full--primary list-item--primary">
												<p><?php esc_html_e( 'Current Salary', 'easyjobs' ); ?></p>
												<p class="label__content"><?php echo esc_html( $data->candidate->user->current_salary ); ?></p>
											</li>
											<li class="label__full  label__full--primary list-item--primary">
												<p><?php esc_html_e( 'Expected Salary', 'easyjobs' ); ?></p>
												<p class="label__content"><?php echo esc_html( $data->candidate->expected_salary ); ?></p>
											</li>
										</ul>
									</div>
								</div>
								<div class="details__card gutter-10 ej-candidate-notes-from">
									<div class="details__card__head">
										<h4>Notes</h4>
									</div>
									<div class="details__card__body">
										<form class="ej-add-note"
											  data-candidate-id="<?php echo ! empty( $_GET['candidate-id'] ) ? esc_attr( $_GET['candidate-id'] ) : ''; ?>">
											<textarea name="note" id="" rows="5" placeholder="Add a note here . . . "
													  class="label__full--primary form-control"></textarea>
											<?php if ( ! empty( $notes ) && ! empty( $notes->managers ) ) : ?>
												<div class="tag-select mt-2">
													<select name="tag_select" id="tag-select" multiple>
														<?php foreach ( $notes->managers as $manager ) : ?>
															<option
																value='<?php echo esc_html( json_encode( $manager ) ); ?>'>
																<?php echo esc_html( $manager->name ); ?>
															</option>
														<?php endforeach; ?>
													</select>
												</div>
											<?php endif; ?>
											<div class="form-action mt-4">
												<button class="semi-button semi-button-danger">Cancel</button>
												<button class="semi-button semi-button-info" type="submit">Save</button>
											</div>
										</form>
									</div>
								</div>
								<div class="details__card gutter-10 ej-candidate-notes">
									<div class="details__card__body">
										<?php if ( ! empty( $notes ) && ! empty( $notes->notes ) ) : ?>
											<?php foreach ( $notes->notes as $note ) : ?>
												<div class="note-info"
													 data-note-id="<?php echo esc_html( $note->id ); ?>">
													<a href="#" class="erase-button">
														<i class="easyjobs-icon easyjobs-delete"></i>
													</a>
													<p class="label__full--eraseble label__full--primary">
														<?php echo esc_html( $note->note ); ?>
														<?php if ( ! empty( $note->tags ) ) : ?>
															<?php foreach ( $note->tags as $note_tag ) : ?>
																<span class="tag-container">
                                                                    <span class="tag-name">@<?php echo esc_html( $note_tag->name ); ?></span>
                                                                </span>
															<?php endforeach; ?>
														<?php endif; ?>
													</p>
													<div class="note-admin-info">
														<div class="note-by">
															<strong>
																<?php echo esc_html( $note->creator ); ?>
															</strong>
														</div>
														<div class="note-time">
															<span><?php echo esc_html( $note->created_at ); ?></span>
														</div>
													</div>
												</div>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</section>
					<div class="dg-wrapper ratting-confirmation">
						<div class="dg-backdrop"></div>
						<div class="dg-container">
							<div class="dg-content-cont dg-content-cont--floating">
								<div class="dg-main-content">
									<div class="dg-view-wrapper">
										<div class="dg-content-body dg-content-body--has-title"><h6 class="dg-title">
												Confirmation</h6>
											<div class="dg-content">Are you sure, you want to remove rating for this
												candidate?
											</div>
										</div>
										<div class="dg-content-footer">
											<button class="dg-btn dg-btn--cancel remove-rating-cancel">
												<span>No</span>
											</button>
											<button class="dg-btn dg-btn--ok dg-pull-right remove-rating">
												<span class="dg-btn-content">Yes</span>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="dg-wrapper ej-candidate-confirmation" id="ej-candidate-confirmation">
						<div class="dg-backdrop"></div>
						<div class="dg-container">
							<div class="dg-content-cont dg-content-cont--floating">
								<div class="dg-main-content">
									<div class="dg-view-wrapper">
										<div class="dg-content-body dg-content-body--has-title"><h6 class="dg-title">
												Confirmation</h6>
											<div class="dg-content">Are you sure, you want to remove rating for this
												candidate?
											</div>
										</div>
										<div class="dg-content-footer">
											<button class="dg-btn dg-btn--cancel remove-rating-cancel">
												<span>No</span>
											</button>
											<button class="dg-btn dg-btn--ok dg-pull-right ej-candidate-action-confirm">
												<span class="dg-btn-content">Yes</span>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main>
		</div>
	<?php endif; ?>
</div>
