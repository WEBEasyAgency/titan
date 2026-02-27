<?php
/**
 * My Account Dashboard — Profile tab.
 * Overrides WooCommerce default dashboard.php
 */
defined( 'ABSPATH' ) || exit;

$user      = wp_get_current_user();
$user_id   = $user->ID;
$last_name  = $user->last_name;
$first_name = $user->first_name;
$surname    = get_user_meta( $user_id, 'surname', true );
$full_name  = trim( $last_name . ' ' . $first_name . ' ' . $surname );
$email      = $user->user_email;
$phone      = get_user_meta( $user_id, 'billing_phone', true );
$user_type_raw = get_user_meta( $user_id, 'user_type', true );
$user_type  = $user_type_raw === 'business' ? 'Юридическое лицо' : 'Физическое лицо';

$legal_entities = titan_get_legal_entities( $user_id );
?>

<!-- Profile Card -->
<div class="profile-card">
	<div class="profile-card__header">
		<div class="profile-card__info">
			<h2 class="profile-card__name"><?php echo esc_html( $full_name ); ?></h2>
			<div class="profile-card__type"><?php echo esc_html( $user_type ); ?></div>
		</div>
		<div class="profile-card__contacts">
			<div class="profile-card__email"><?php echo esc_html( $email ); ?></div>
			<?php if ( $phone ) : ?>
				<div class="profile-card__phone"><?php echo esc_html( $phone ); ?></div>
			<?php endif; ?>
		</div>
	</div>

	<!-- View Actions -->
	<div class="profile-card__actions" id="profile-view-actions">
		<button class="btn" id="btn-edit-profile">Редактировать</button>
		<button class="btn" id="btn-change-password">Изменить пароль</button>
	</div>

	<!-- Password Form -->
	<div class="profile-card__password-form" id="password-form" style="display: none;">
		<div class="password-fields">
			<label class="password">
				<input type="password" name="current_password" placeholder="Текущий пароль">
				<div class="visible__text"></div>
			</label>
			<label class="password">
				<input type="password" name="new_password" placeholder="Новый пароль">
				<div class="visible__text"></div>
			</label>
			<label class="password">
				<input type="password" name="confirm_password" placeholder="Повторите пароль">
				<div class="visible__text"></div>
			</label>
		</div>
		<button class="btn" id="btn-save-password">Сохранить изменения</button>
	</div>

	<!-- Edit Profile Form -->
	<div class="profile-card__edit-form" id="edit-profile-form" style="display: none;">
		<div class="profile-card__separator"></div>
		<div class="profile-card__edit-title">Редактирование данных</div>
		<div class="profile-edit-fields">
			<input type="text" name="lastname" value="<?php echo esc_attr( $last_name ); ?>" placeholder="Фамилия">
			<input type="text" name="firstname" value="<?php echo esc_attr( $first_name ); ?>" placeholder="Имя">
			<input type="text" name="patronymic" value="<?php echo esc_attr( $surname ); ?>" placeholder="Отчество">
			<input type="email" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="Почта" disabled>
			<input type="tel" name="phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="Телефон">
		</div>
		<label class="checkbox">
			<input type="checkbox" name="personal_data">
			<span class="check"></span>
			<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ «О персональных данных»</span>
		</label>
		<div class="profile-card__edit-actions">
			<button class="btn btn-outline" id="btn-cancel-edit">Не сохранять</button>
			<button class="btn" id="btn-save-profile">Сохранить изменения</button>
		</div>
	</div>
</div>

<!-- Legal Entities -->
<div class="legal-entities">
	<h2 class="legal-entities__title">Юридические лица</h2>

	<div class="legal-entities__list" id="legal-entities-list">
		<?php foreach ( $legal_entities as $entity ) : ?>
		<div class="legal-entity" data-id="<?php echo esc_attr( $entity['id'] ); ?>">
			<div class="legal-entity__header">
				<div class="legal-entity__name"><?php echo esc_html( $entity['org_name'] ); ?></div>
				<div class="legal-entity__controls">
					<button class="legal-entity__btn-edit" title="Редактировать">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M15.7279 9.57629L14.3137 8.16207L5 17.4758V18.8901H6.41421L15.7279 9.57629ZM17.1421 8.16207L18.5563 6.74786L17.1421 5.33365L15.7279 6.74786L17.1421 8.16207ZM7.24264 20.8901H3V16.6474L16.435 3.21233C16.8256 2.8218 17.4587 2.8218 17.8492 3.21233L20.6777 6.04075C21.0682 6.43128 21.0682 7.06444 20.6777 7.45497L7.24264 20.8901Z" fill="black"/>
						</svg>
					</button>
					<button class="legal-entity__btn-delete" title="Удалить">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M20 5C20 5.55228 19.5523 6 19 6H18.997L18.064 19.142C18.0281 19.6466 17.8023 20.1188 17.4321 20.4636C17.0619 20.8083 16.5749 20.9999 16.069 21H7.93C7.42414 20.9999 6.93707 20.8083 6.56688 20.4636C6.19669 20.1188 5.97093 19.6466 5.935 19.142L5.003 6H5C4.44772 6 4 5.55228 4 5C4 4.44772 4.44772 4 5 4H9C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4H19C19.5523 4 20 4.44772 20 5ZM7.003 6L7.931 19H16.069L16.997 6H7.003Z" fill="black"/>
						</svg>
					</button>
				</div>
			</div>
			<!-- Edit Form (hidden) -->
			<div class="legal-entity__edit-form" style="display: none;">
				<div class="legal-entity__separator"></div>
				<div class="legal-entity__edit-title">Редактирование данных</div>
				<div class="legal-entity__fields">
					<input type="text" name="org_name" value="<?php echo esc_attr( $entity['org_name'] ); ?>" placeholder="Наименование организации">
					<input type="text" name="inn" value="<?php echo esc_attr( $entity['inn'] ); ?>" placeholder="ИНН Организации или ИП">
					<input type="text" name="kpp" value="<?php echo esc_attr( $entity['kpp'] ); ?>" placeholder="КПП Организации или ИП">
					<input type="text" name="legal_address" value="<?php echo esc_attr( $entity['address'] ); ?>" placeholder="Адрес юридический">
					<input type="text" name="postal_code" value="<?php echo esc_attr( $entity['postal_code'] ); ?>" placeholder="Почтовый индекс">
					<input type="text" name="region" value="<?php echo esc_attr( $entity['region'] ); ?>" placeholder="Область/край/республика">
					<input type="text" name="district" value="<?php echo esc_attr( $entity['district'] ); ?>" placeholder="Район">
					<input type="text" name="city" value="<?php echo esc_attr( $entity['city'] ); ?>" placeholder="Населенный пункт">
					<input type="text" name="address" value="<?php echo esc_attr( $entity['office'] ); ?>" placeholder="Дом/офис/квартира">
				</div>
				<label class="checkbox">
					<input type="checkbox" name="authorized" checked>
					<span class="check"></span>
					<span class="label">Я подтверждаю, что уполномочен представлять интересы данного юридического лица или ИП</span>
				</label>
				<label class="checkbox">
					<input type="checkbox" name="personal_data" checked>
					<span class="check"></span>
					<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ «О персональных данных»</span>
				</label>
				<div class="legal-entity__edit-actions">
					<button class="btn btn-outline legal-entity__btn-cancel">Не сохранять</button>
					<button class="btn legal-entity__btn-save">Сохранить изменения</button>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>

	<button class="btn" id="btn-add-legal-entity">+ Новое юридическое лицо</button>

	<!-- Add New Legal Entity Form (hidden) -->
	<div class="legal-entity__add-form" id="add-legal-entity-form" style="display: none;">
		<div class="legal-entity__fields">
			<input type="text" name="org_name" placeholder="Наименование организации">
			<input type="text" name="inn" placeholder="ИНН Организации или ИП">
			<input type="text" name="kpp" placeholder="КПП Организации или ИП">
			<input type="text" name="legal_address" placeholder="Адрес юридический">
			<input type="text" name="postal_code" placeholder="Почтовый индекс">
			<input type="text" name="region" placeholder="Область/край/республика">
			<input type="text" name="district" placeholder="Район">
			<input type="text" name="city" placeholder="Населенный пункт">
			<input type="text" name="address" placeholder="Дом/офис/квартира">
		</div>
		<label class="checkbox">
			<input type="checkbox" name="authorized">
			<span class="check"></span>
			<span class="label">Я подтверждаю, что уполномочен представлять интересы данного юридического лица или ИП</span>
		</label>
		<label class="checkbox">
			<input type="checkbox" name="personal_data">
			<span class="check"></span>
			<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ «О персональных данных»</span>
		</label>
		<div class="legal-entity__edit-actions">
			<button class="btn btn-outline legal-entity__btn-cancel-add">Не сохранять</button>
			<button class="btn btn-disabled legal-entity__btn-add" disabled>Добавить</button>
		</div>
	</div>
</div>
