<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisition_request_forms', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('requesting_unit')->nullable();
            $table->unsignedBigInteger('head_of_department_id')->nullable();
            $table->unsignedBigInteger('contact_person_id')->nullable();
            $table->unsignedBigInteger('approvalOfficer')->nullable();
            $table->unsignedBigInteger('requisition_id')->nullable();

            $table->date('date')->nullable();
            $table->string('contact_info')->nullable();
            $table->text('justification_path')->nullable();
            $table->string('location_of_delivery')->nullable();
            $table->date('date_required_by')->nullable();

            $table->decimal('estimated_value', 10, 2)->nullable();
            $table->boolean('availability_of_funds')->default(false);
            $table->boolean('verified_by_accounts')->default(false);

            $table->dateTime('hod_approval_date')->nullable();
            $table->boolean('hod_approval')->default(false);
            $table->dateTime('approval_officer_approval_date')->nullable();
            $table->boolean('approval_officer_approval')->default(false);

            $table->string('status')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('requesting_unit')
                ->references('id')
                ->on('departments');

            $table->foreign('head_of_department_id')
                ->references('id')
                ->on('users');

            $table->foreign('contact_person_id')
                ->references('id')
                ->on('users');

            $table->foreign('approvalOfficer')
                ->references('id')
                ->on('users');

            $table->foreign('requisition_id')
                ->references('id')
                ->on('requisitions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisition_request_forms');
    }
};
