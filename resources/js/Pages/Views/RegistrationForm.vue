<template>
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white">
            <div
                class="bg-gradient-to-r from-blue-600 to-blue-500 shadow-lg m-8"
            >
                <div class="mx-auto px-4 py-8 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <div
                            class="inline-flex items-center justify-center bg-white/10 backdrop-blur-sm rounded-full p-2 mb-4"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6M16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6"
                                />
                            </svg>
                        </div>
                        <h1 class="text-4xl font-bold text-white">
                            Galeri Sejarah CMS Installation
                        </h1>
                        <p class="mt-3 text-xl text-blue-100 max-w-2xl mx-auto">
                            Follow these simple steps to set up your historical
                            gallery content management system
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-12 p-4 bg-blue-50 rounded-lg">
                <nav class="flex items-center justify-center">
                    <ol class="flex items-center">
                        <li
                            v-for="(step, index) in steps"
                            :key="step.name"
                            class="flex items-center"
                        >
                            <!-- Step Circle -->
                            <div class="relative flex flex-col items-center">
                                <div
                                    :class="`w-12 h-12 rounded-full flex items-center justify-center text-lg font-semibold border-2 transition-all duration-300 ${
                                        currentStep > step.step
                                            ? 'bg-green-500 text-white border-green-500 shadow-lg'
                                            : currentStep === step.step
                                            ? 'bg-white text-blue-600 border-blue-600 shadow-lg ring-4 ring-blue-200'
                                            : 'bg-white/80 text-gray-400 border-gray-300'
                                    }`"
                                >
                                    <span v-if="currentStep > step.step">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </span>
                                    <span v-else>{{ step.step }}</span>
                                </div>
                                <!-- Step Name -->
                                <span
                                    :class="`mt-3 text-sm font-medium transition-all duration-300 ${
                                        currentStep >= step.step
                                            ? 'text-blue-700 font-semibold'
                                            : 'text-gray-500'
                                    }`"
                                >
                                    {{ step.name }}
                                </span>
                            </div>

                            <!-- Connector Line -->
                            <div
                                v-if="index < steps.length - 1"
                                :class="`w-16 h-1 mx-2 mb-6 transition-all duration-500 ${
                                    currentStep > step.step
                                        ? 'bg-gradient-to-r from-green-500 to-blue-600'
                                        : 'bg-gray-200'
                                }`"
                            ></div>
                        </li>
                    </ol>
                </nav>

                <!-- Progress Status Text -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-blue-600 font-medium">
                        Step {{ currentStep }} of {{ steps.length }}:
                        {{ steps[currentStep - 1].description }}
                    </p>
                    <div
                        class="mt-2 w-full bg-gray-200 rounded-full h-2 mx-auto"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500 ease-out"
                            :style="`width: ${
                                ((currentStep - 1) / (steps.length - 1)) * 100
                            }%`"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Step 1: Requirements Check -->
                <!-- Step 1: Requirements Check -->
                <div
                    v-if="currentStep === 1"
                    class="min-h-screen bg-gradient-to-br from-blue-50 to-white p-6"
                >
                    <div class="m-8">
                        <!-- Header with progress indicator -->
                        <div
                            class="flex flex-col items-center md:flex-row justify-between md:items-center mb-8"
                        >
                            <div class="mx-auto">
                                <h2
                                    class="text-3xl font-bold text-blue-900 mb-2 text-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-16 w-16 text-blue-900 mx-auto"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                                        />
                                    </svg>
                                    System Requirements Check
                                </h2>
                                <p class="text-blue-700 ml-16">
                                    Let's verify your server meets all
                                    requirements for optimal performance
                                </p>
                            </div>
                            <div
                                class="bg-white px-4 py-2 rounded-full shadow-sm border border-blue-100 mt-4 md:mt-0"
                            >
                                <span class="text-blue-600 font-medium"
                                    >Step 1 of 3</span
                                >
                            </div>
                        </div>

                        <!-- Main content container -->
                        <div
                            class="bg-white rounded-2xl shadow-xl overflow-hidden border border-blue-100"
                        >
                            <!-- Info banner -->
                            <div
                                class="bg-blue-50 p-4 border-b border-blue-100"
                            >
                                <div class="flex items-start">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <p class="text-sm text-blue-800">
                                        This step automatically checks if your
                                        server environment meets all technical
                                        requirements.
                                        <span class="font-medium"
                                            >Don't worry</span
                                        >
                                        - we'll guide you through fixing any
                                        issues if found.
                                    </p>
                                </div>
                            </div>

                            <div class="p-8">
                                <!-- Version Requirements -->
                                <div class="mb-10">
                                    <div
                                        class="flex items-center justify-between mb-6"
                                    >
                                        <h3
                                            class="text-xl font-bold text-gray-800 flex items-center"
                                        >
                                            <span
                                                class="bg-blue-100 text-blue-800 p-2 rounded-lg mr-3"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                            </span>
                                            Software Versions
                                        </h3>
                                        <span
                                            class="text-sm font-medium px-3 py-1 rounded-full"
                                            :class="
                                                allVersionRequirementsMet
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-yellow-100 text-yellow-800'
                                            "
                                        >
                                            {{
                                                allVersionRequirementsMet(
                                                    "All requirements met"
                                                )
                                            }}
                                        </span>
                                    </div>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                                    >
                                        <div
                                            v-for="(
                                                version, name
                                            ) in requirements.versions"
                                            :key="name"
                                            class="bg-gradient-to-br from-white to-blue-50 rounded-xl p-5 border border-blue-100 hover:border-blue-200 transition-all shadow-sm hover:shadow-md"
                                        >
                                            <div class="flex items-start">
                                                <div
                                                    class="flex-shrink-0 mt-0.5"
                                                >
                                                    <div
                                                        class="h-10 w-10 rounded-lg flex items-center justify-center"
                                                        :class="
                                                            version.supported
                                                                ? 'bg-green-100 text-green-800'
                                                                : 'bg-red-100 text-red-800'
                                                        "
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                        >
                                                            <path
                                                                v-if="
                                                                    version.supported
                                                                "
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M5 13l4 4L19 7"
                                                            />
                                                            <path
                                                                v-else
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12"
                                                            />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <h4
                                                        class="font-semibold text-gray-900 capitalize"
                                                    >
                                                        {{ name }}
                                                    </h4>
                                                    <div
                                                        class="mt-2 space-y-2 text-sm"
                                                    >
                                                        <div
                                                            class="flex justify-between"
                                                        >
                                                            <span
                                                                class="text-gray-500"
                                                                >Your
                                                                version:</span
                                                            >
                                                            <span
                                                                class="font-medium"
                                                                :class="
                                                                    version.supported
                                                                        ? 'text-green-700'
                                                                        : 'text-red-700'
                                                                "
                                                            >
                                                                {{
                                                                    version.current ||
                                                                    "Not detected"
                                                                }}
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="flex justify-between"
                                                        >
                                                            <span
                                                                class="text-gray-500"
                                                                >Minimum
                                                                required:</span
                                                            >
                                                            <span
                                                                class="font-medium text-blue-700"
                                                                >{{
                                                                    version.minimum
                                                                }}</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            !version.supported
                                                        "
                                                        class="mt-3 text-xs bg-red-50 text-red-700 p-2 rounded"
                                                    >
                                                        <span
                                                            class="font-medium"
                                                            >All requirements
                                                            met</span
                                                        >
                                                        Please upgrade your
                                                        {{ name }} version to at
                                                        least
                                                        {{ version.minimum }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PHP Extensions -->
                                <div class="mb-10">
                                    <div
                                        class="flex items-center justify-between mb-6"
                                    >
                                        <h3
                                            class="text-xl font-bold text-gray-800 flex items-center"
                                        >
                                            <span
                                                class="bg-blue-100 text-blue-800 p-2 rounded-lg mr-3"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                                                    />
                                                </svg>
                                            </span>
                                            PHP Extensions
                                        </h3>
                                        <span
                                            class="text-sm font-medium px-3 py-1 rounded-full"
                                            :class="
                                                allPhpRequirementsMet
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-yellow-100 text-yellow-800'
                                            "
                                        >
                                            {{
                                                allPhpRequirementsMet
                                                    ? "All extensions loaded"
                                                    : "Missing extensions"
                                            }}
                                        </span>
                                    </div>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3"
                                    >
                                        <div
                                            v-for="req in phpRequirements"
                                            :key="req.name"
                                            class="bg-white rounded-lg p-4 border hover:border-blue-300 transition-colors"
                                            :class="
                                                req.status
                                                    ? 'border-green-200'
                                                    : 'border-red-200'
                                            "
                                        >
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <span
                                                    class="font-medium text-gray-800"
                                                    >{{ req.name }}</span
                                                >
                                                <span
                                                    class="text-xs font-semibold px-2 py-1 rounded-full"
                                                    :class="
                                                        req.status
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-red-100 text-red-800'
                                                    "
                                                >
                                                    {{
                                                        req.status
                                                            ? "Loaded"
                                                            : "Missing"
                                                    }}
                                                </span>
                                            </div>
                                            <div
                                                v-if="!req.status"
                                                class="mt-2 text-xs text-red-600"
                                            >
                                                Contact your hosting provider to
                                                enable this PHP extension
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Folder Permissions -->
                                <div class="mb-8">
                                    <div
                                        class="flex items-center justify-between mb-6"
                                    >
                                        <h3
                                            class="text-xl font-bold text-gray-800 flex items-center"
                                        >
                                            <span
                                                class="bg-blue-100 text-blue-800 p-2 rounded-lg mr-3"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
                                                    />
                                                </svg>
                                            </span>
                                            Folder Permissions
                                        </h3>
                                        <span
                                            class="text-sm font-medium px-3 py-1 rounded-full"
                                            :class="
                                                allPermissionsMet
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-yellow-100 text-yellow-800'
                                            "
                                        >
                                            {{
                                                allPermissionsMet("All correct")
                                            }}
                                        </span>
                                    </div>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                    >
                                        <div
                                            v-for="perm in requirements.permissions"
                                            :key="perm.folder"
                                            class="bg-white rounded-xl p-5 border hover:shadow-md transition-all"
                                            :class="
                                                perm.status
                                                    ? 'border-green-200'
                                                    : 'border-red-200'
                                            "
                                        >
                                            <div class="flex items-start">
                                                <div
                                                    class="flex-shrink-0 mt-0.5"
                                                >
                                                    <div
                                                        class="h-10 w-10 rounded-lg flex items-center justify-center"
                                                        :class="
                                                            perm.status
                                                                ? 'bg-green-100 text-green-800'
                                                                : 'bg-red-100 text-red-800'
                                                        "
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                        >
                                                            <path
                                                                v-if="
                                                                    perm.status
                                                                "
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M5 13l4 4L19 7"
                                                            />
                                                            <path
                                                                v-else
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                                            />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <h4
                                                        class="font-semibold text-gray-900"
                                                    >
                                                        {{ perm.folder }}
                                                    </h4>
                                                    <div
                                                        class="mt-2 space-y-2 text-sm"
                                                    >
                                                        <div
                                                            class="flex justify-between"
                                                        >
                                                            <span
                                                                class="text-gray-500"
                                                                >Current
                                                                permission:</span
                                                            >
                                                            <span
                                                                class="font-medium"
                                                                :class="
                                                                    perm.status
                                                                        ? 'text-green-700'
                                                                        : 'text-red-700'
                                                                "
                                                            >
                                                                {{
                                                                    perm.current
                                                                }}
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="flex justify-between"
                                                        >
                                                            <span
                                                                class="text-gray-500"
                                                                >Required
                                                                permission:</span
                                                            >
                                                            <span
                                                                class="font-medium text-blue-700"
                                                                >{{
                                                                    perm.required
                                                                }}</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div
                                                        v-if="!perm.status"
                                                        class="mt-3 text-xs bg-red-50 text-red-700 p-2 rounded"
                                                    >
                                                        <span
                                                            class="font-medium"
                                                            >How to fix:</span
                                                        >
                                                        Set folder permission to
                                                        {{ perm.required }}
                                                        using your FTP client or
                                                        hosting file manager
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Help Section -->
                                <div
                                    class="mt-8 bg-gradient-to-r from-blue-50 to-white rounded-2xl shadow-sm border border-blue-200 p-6"
                                >
                                    <h3
                                        class="text-lg font-semibold text-gray-900 mb-4 flex items-center"
                                    >
                                        <span
                                            class="bg-blue-600 text-white p-2 rounded-lg mr-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </span>
                                        Need Help With Server Requirements?
                                    </h3>
                                    <div
                                        class="grid md:grid-cols-2 gap-4 text-sm"
                                    >
                                        <div
                                            class="bg-white rounded-xl p-5 border border-blue-200 hover:border-blue-300 transition-colors"
                                        >
                                            <h4
                                                class="font-bold text-blue-900 mb-3 flex items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-blue-600 mr-2"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                                    />
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />
                                                </svg>
                                                PHP Version Issues
                                            </h4>
                                            <p class="text-gray-700 mb-2">
                                                If your PHP version is too old,
                                                contact your hosting provider to
                                                upgrade it. Most modern hosts
                                                allow you to select PHP version
                                                in your control panel.
                                            </p>
                                            <a
                                                href="#"
                                                class="text-blue-600 hover:underline font-medium inline-flex items-center"
                                            >
                                                Learn how to change PHP version
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 ml-1"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5l7 7-7 7"
                                                    />
                                                </svg>
                                            </a>
                                        </div>
                                        <div
                                            class="bg-white rounded-xl p-5 border border-blue-200 hover:border-blue-300 transition-colors"
                                        >
                                            <h4
                                                class="font-bold text-blue-900 mb-3 flex items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-blue-600 mr-2"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                    />
                                                </svg>
                                                Permission Problems
                                            </h4>
                                            <p class="text-gray-700 mb-2">
                                                For folder permission issues,
                                                use your FTP client (like
                                                FileZilla) to right-click the
                                                folder, select "File
                                                Permissions" and set to the
                                                required value.
                                            </p>
                                            <a
                                                href="#"
                                                class="text-blue-600 hover:underline font-medium inline-flex items-center"
                                            >
                                                FTP permission guide
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 ml-1"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5l7 7-7 7"
                                                    />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation -->
                                <div
                                    class="mt-10 pt-8 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center"
                                >
                                    <div class="mb-4 sm:mb-0">
                                        <div
                                            v-if="!allRequirementsMet"
                                            class="flex items-center bg-yellow-50 text-yellow-800 px-4 py-3 rounded-lg"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 mr-2"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Please fix all requirements
                                                issues before proceeding</span
                                            >
                                        </div>
                                    </div>
                                    <button
                                        @click="nextStep"
                                        :disabled="
                                            !allRequirementsMet || loading
                                        "
                                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-600 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                                    >
                                        <span>Continue to Configuration</span>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 ml-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Application Configuration -->
                <div
                    v-if="currentStep === 2"
                    class="min-h-screen bg-gradient-to-br from-blue-50 to-white p-6"
                >
                    <div class="m-8">
                        <!-- Header with progress indicator -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8"
                        >
                            <div class="mx-auto">
                                <h2
                                    class="text-3xl font-bold text-blue-900 mb-2 text-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-16 w-16 text-blue-900 mx-auto"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                    Application Configuration
                                </h2>
                                <p class="text-blue-700 ml-16">
                                    Configure your application settings and
                                    database connection
                                </p>
                            </div>
                            <div
                                class="bg-white px-4 py-2 rounded-full shadow-sm border border-blue-100 mt-4 md:mt-0"
                            >
                                <span class="text-blue-600 font-medium"
                                    >Step 2 of 3</span
                                >
                            </div>
                        </div>

                        <!-- Main form container -->
                        <div
                            class="bg-white rounded-2xl shadow-xl overflow-hidden border border-blue-100"
                        >
                            <!-- Info banner -->
                            <div
                                class="bg-blue-50 p-4 border-b border-blue-100"
                            >
                                <div class="flex items-start">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <p class="text-sm text-blue-800">
                                        Fill in the details below carefully. You
                                        can always change these settings later
                                        in the configuration files.
                                    </p>
                                </div>
                            </div>

                            <form
                                @submit.prevent="saveConfiguration"
                                class="p-8"
                            >
                                <!-- Application Settings -->
                                <div class="mb-10">
                                    <h3
                                        class="text-xl font-bold text-gray-800 mb-6 flex items-center"
                                    >
                                        <span
                                            class="bg-blue-100 text-blue-800 p-2 rounded-lg mr-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                                />
                                            </svg>
                                        </span>
                                        Environment Settings
                                    </h3>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <!-- App Name -->
                                        <div class="relative">
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Application Name
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <input
                                                    v-model="form.app_name"
                                                    type="text"
                                                    required
                                                    placeholder="My Awesome App"
                                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                                />
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-gray-400"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                This name will appear in emails
                                                and browser tabs
                                            </p>
                                        </div>

                                        <!-- App URL -->
                                        <div class="relative">
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Application URL
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <input
                                                    v-model="form.app_url"
                                                    type="url"
                                                    required
                                                    placeholder="https://yourdomain.com"
                                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                                />
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-gray-400"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Must include http:// or https://
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Database Configuration -->
                                <div class="mb-10">
                                    <h3
                                        class="text-xl font-bold text-gray-800 mb-6 flex items-center"
                                    >
                                        <span
                                            class="bg-blue-100 text-blue-800 p-2 rounded-lg mr-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"
                                                />
                                            </svg>
                                        </span>
                                        Database Configuration
                                    </h3>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <!-- Database Name -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Database Name
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <input
                                                v-model="form.db_name"
                                                type="text"
                                                required
                                                placeholder="my_app_database"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            />
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Name of your database (must
                                                exist already)
                                            </p>
                                        </div>

                                        <!-- Database User -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Database User
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <input
                                                v-model="form.db_user"
                                                type="text"
                                                required
                                                placeholder="db_user"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            />
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                User with access to this
                                                database
                                            </p>
                                        </div>

                                        <!-- Database Password -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Database Password
                                            </label>
                                            <div class="relative">
                                                <input
                                                    v-model="form.db_pass"
                                                    :type="
                                                        showDbPassword
                                                            ? 'text'
                                                            : 'password'
                                                    "
                                                    placeholder="••••••••"
                                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                                />
                                                <button
                                                    type="button"
                                                    @click="
                                                        showDbPassword =
                                                            !showDbPassword
                                                    "
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-gray-400 hover:text-gray-500"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            v-if="
                                                                showDbPassword
                                                            "
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Leave empty if no password is
                                                set
                                            </p>
                                        </div>

                                        <!-- Database Host -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Database Host
                                            </label>
                                            <input
                                                v-model="form.db_host"
                                                type="text"
                                                placeholder="localhost"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            />
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Usually 'localhost' for most
                                                hosting
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Help Section -->
                                <div
                                    class="mt-8 bg-gradient-to-r from-blue-50 to-white rounded-2xl shadow-sm border border-blue-200 p-6"
                                >
                                    <h3
                                        class="text-lg font-semibold text-gray-900 mb-4 flex items-center"
                                    >
                                        <span
                                            class="bg-blue-600 text-white p-2 rounded-lg mr-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </span>
                                        Need Help With Database Setup?
                                    </h3>
                                    <div
                                        class="grid md:grid-cols-2 gap-4 text-sm"
                                    >
                                        <div
                                            class="bg-white rounded-xl p-5 border border-blue-200 hover:border-blue-300 transition-colors"
                                        >
                                            <h4
                                                class="font-bold text-blue-900 mb-3 flex items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-blue-600 mr-2"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2zm0 4a2 2 0 000-4H3a2 2 0 000 4h18zm0 4a2 2 0 000-4H3a2 2 0 000 4h18z"
                                                    />
                                                </svg>
                                                Where to Find Database Details
                                            </h4>
                                            <p class="text-gray-700 mb-2">
                                                These details are usually
                                                available in your hosting
                                                control panel (like cPanel)
                                                under "MySQL Databases" or in
                                                the welcome email from your
                                                hosting provider.
                                            </p>
                                            <a
                                                href="#"
                                                class="text-blue-600 hover:underline font-medium inline-flex items-center"
                                            >
                                                How to create a database
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 ml-1"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5l7 7-7 7"
                                                    />
                                                </svg>
                                            </a>
                                        </div>
                                        <div
                                            class="bg-white rounded-xl p-5 border border-blue-200 hover:border-blue-300 transition-colors"
                                        >
                                            <h4
                                                class="font-bold text-blue-900 mb-3 flex items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-blue-600 mr-2"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                                    />
                                                </svg>
                                                Connection Issues?
                                            </h4>
                                            <p class="text-gray-700 mb-2">
                                                If you're having connection
                                                problems, verify your database
                                                credentials and ensure your
                                                database user has proper
                                                permissions.
                                            </p>
                                            <a
                                                href="#"
                                                class="text-blue-600 hover:underline font-medium inline-flex items-center"
                                            >
                                                Troubleshooting guide
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 ml-1"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5l7 7-7 7"
                                                    />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div
                                    v-if="error"
                                    class="mt-6 bg-red-50 border-l-4 border-red-500 rounded-r-lg p-4"
                                >
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-5 w-5 text-red-500"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3
                                                class="text-sm font-medium text-red-800"
                                            >
                                                Configuration Error
                                            </h3>
                                            <div
                                                class="mt-1 text-sm text-red-700"
                                            >
                                                <p>{{ error }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation -->
                                <div
                                    class="mt-10 pt-8 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center"
                                >
                                    <button
                                        type="button"
                                        @click="prevStep"
                                        class="px-8 py-3 text-gray-700 rounded-xl font-medium hover:bg-gray-100 transition-colors border border-gray-300 mb-4 sm:mb-0 flex items-center"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                            />
                                        </svg>
                                        Back to Requirements
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="loading"
                                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-600 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                                    >
                                        <span v-if="loading">
                                            <svg
                                                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <circle
                                                    class="opacity-25"
                                                    cx="12"
                                                    cy="12"
                                                    r="10"
                                                    stroke="currentColor"
                                                    stroke-width="4"
                                                ></circle>
                                                <path
                                                    class="opacity-75"
                                                    fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                ></path>
                                            </svg>
                                            Saving Configuration...
                                        </span>
                                        <span v-else class="flex items-center">
                                            Save & Continue
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 ml-2"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                                />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div
                    v-if="currentStep === 3"
                    class="min-h-screen bg-gradient-to-br from-blue-50 to-white p-6"
                >
                    <div class="m-8">
                        <!-- Header with progress indicator -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8"
                        >
                            <div class="mx-auto">
                                <h2
                                    class="text-3xl font-bold text-blue-900 mb-2 text-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-16 w-16 text-blue-900 mx-auto"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                        />
                                    </svg>
                                    Company Registration
                                </h2>
                                <p class="text-blue-700 ml-16">
                                    Set up your organization's profile and admin
                                    account
                                </p>
                            </div>
                            <div
                                class="bg-white px-4 py-2 rounded-full shadow-sm border border-blue-100 mt-4 md:mt-0"
                            >
                                <span class="text-blue-600 font-medium"
                                    >Final Step</span
                                >
                            </div>
                        </div>

                        <!-- Main form container -->
                        <div
                            class="bg-white rounded-2xl shadow-xl overflow-hidden border border-blue-100"
                        >
                            <!-- Info banner -->
                            <div
                                class="bg-blue-50 p-4 border-b border-blue-100"
                            >
                                <div class="flex items-start">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <p class="text-sm text-blue-800">
                                        This information will be used to
                                        personalize your experience. You can
                                        update these details later in the admin
                                        panel.
                                    </p>
                                </div>
                            </div>

                            <form
                                @submit.prevent="completeInstallation"
                                class="p-8"
                            >
                                <!-- Success Message -->
                                <div
                                    v-if="success"
                                    class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-r-lg p-4"
                                >
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-5 w-5 text-green-500"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-sm font-medium text-green-800"
                                            >
                                                {{ success }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div
                                    v-if="error"
                                    class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-r-lg p-4"
                                >
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-5 w-5 text-red-500"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3
                                                class="text-sm font-medium text-red-800"
                                            >
                                                Registration Error
                                            </h3>
                                            <div
                                                class="mt-1 text-sm text-red-700"
                                            >
                                                <p>{{ error }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Information -->
                                <div class="mb-10">
                                    <h3
                                        class="text-xl font-bold text-gray-800 mb-6 flex items-center"
                                    >
                                        <span
                                            class="bg-blue-100 text-blue-800 p-2 rounded-lg mr-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                                />
                                            </svg>
                                        </span>
                                        Company Information
                                    </h3>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <!-- Company Name -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Company Name
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <input
                                                v-model="company.name"
                                                type="text"
                                                required
                                                placeholder="Your Company Inc."
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            />
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                This will appear on invoices and
                                                communications
                                            </p>
                                        </div>

                                        <!-- Company Email -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Company Email
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <input
                                                v-model="company.email"
                                                type="email"
                                                required
                                                placeholder="contact@company.com"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            />
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                For official communications
                                            </p>
                                        </div>

                                        <!-- Phone Number -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Phone Number
                                            </label>
                                            <input
                                                v-model="company.phone"
                                                type="tel"
                                                placeholder="+1 (555) 123-4567"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            />
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Include country code if
                                                international
                                            </p>
                                        </div>

                                        <!-- Address -->
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Address
                                            </label>
                                            <textarea
                                                v-model="company.address"
                                                rows="3"
                                                placeholder="123 Business Ave, Suite 100\nCity, State ZIP\nCountry"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            ></textarea>
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Your company's physical address
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Admin Account -->
                                <div class="mb-10">
                                    <h3
                                        class="text-xl font-bold text-gray-800 mb-6 flex items-center"
                                    >
                                        <span
                                            class="bg-blue-100 text-blue-800 p-2 rounded-lg mr-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                />
                                            </svg>
                                        </span>
                                        Administrator Account
                                    </h3>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <!-- Full Name -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Full Name
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <input
                                                v-model="admin.name"
                                                type="text"
                                                required
                                                placeholder="John Doe"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            />
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Your name as the primary
                                                administrator
                                            </p>
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Email
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <input
                                                v-model="admin.email"
                                                type="email"
                                                required
                                                placeholder="admin@company.com"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            />
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Will be used for login and
                                                notifications
                                            </p>
                                        </div>

                                        <!-- Password -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Password
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <input
                                                    v-model="admin.password"
                                                    :type="
                                                        showPassword
                                                            ? 'text'
                                                            : 'password'
                                                    "
                                                    required
                                                    placeholder="••••••••"
                                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                                />
                                                <button
                                                    type="button"
                                                    @click="
                                                        showPassword =
                                                            !showPassword
                                                    "
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-gray-400 hover:text-gray-500"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            v-if="showPassword"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Minimum 8 characters with
                                                letters and numbers
                                            </p>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Confirm Password
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <input
                                                    v-model="
                                                        admin.password_confirmation
                                                    "
                                                    :type="
                                                        showPassword
                                                            ? 'text'
                                                            : 'password'
                                                    "
                                                    required
                                                    placeholder="••••••••"
                                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                                />
                                                <button
                                                    type="button"
                                                    @click="
                                                        showPassword =
                                                            !showPassword
                                                    "
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-gray-400 hover:text-gray-500"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            v-if="showPassword"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Must match your password
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Help Section -->
                                <div
                                    class="mt-8 bg-gradient-to-r from-blue-50 to-white rounded-2xl shadow-sm border border-blue-200 p-6"
                                >
                                    <h3
                                        class="text-lg font-semibold text-gray-900 mb-4 flex items-center"
                                    >
                                        <span
                                            class="bg-blue-600 text-white p-2 rounded-lg mr-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </span>
                                        Need Help With Registration?
                                    </h3>
                                    <div
                                        class="grid md:grid-cols-2 gap-4 text-sm"
                                    >
                                        <div
                                            class="bg-white rounded-xl p-5 border border-blue-200 hover:border-blue-300 transition-colors"
                                        >
                                            <h4
                                                class="font-bold text-blue-900 mb-3 flex items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-blue-600 mr-2"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                    />
                                                </svg>
                                                About Admin Account
                                            </h4>
                                            <p class="text-gray-700 mb-2">
                                                This account will have full
                                                access to all system features.
                                                Make sure to use a secure
                                                password and an email you have
                                                access to.
                                            </p>
                                        </div>
                                        <div
                                            class="bg-white rounded-xl p-5 border border-blue-200 hover:border-blue-300 transition-colors"
                                        >
                                            <h4
                                                class="font-bold text-blue-900 mb-3 flex items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-blue-600 mr-2"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                                    />
                                                </svg>
                                                Password Security
                                            </h4>
                                            <p class="text-gray-700 mb-2">
                                                We recommend using a password
                                                manager to generate and store a
                                                strong, unique password for your
                                                admin account.
                                            </p>
                                            <a
                                                href="#"
                                                class="text-blue-600 hover:underline font-medium inline-flex items-center"
                                            >
                                                Password best practices
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 ml-1"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5l7 7-7 7"
                                                    />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation -->
                                <div
                                    class="mt-10 pt-8 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center"
                                >
                                    <button
                                        type="button"
                                        @click="prevStep"
                                        class="px-8 py-3 text-gray-700 rounded-xl font-medium hover:bg-gray-100 transition-colors border border-gray-300 mb-4 sm:mb-0 flex items-center"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                            />
                                        </svg>
                                        Back to Configuration
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="loading"
                                        class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-500 text-white rounded-xl font-semibold hover:from-green-700 hover:to-green-600 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                                    >
                                        <span v-if="loading">
                                            <svg
                                                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <circle
                                                    class="opacity-25"
                                                    cx="12"
                                                    cy="12"
                                                    r="10"
                                                    stroke="currentColor"
                                                    stroke-width="4"
                                                ></circle>
                                                <path
                                                    class="opacity-75"
                                                    fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                ></path>
                                            </svg>
                                            Finalizing Setup...
                                        </span>
                                        <span v-else class="flex items-center">
                                            Complete Installation
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 ml-2"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5 13l4 4L19 7"
                                                />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            currentStep: parseInt(sessionStorage.getItem("install_step")) || 1,
            loading: false,
            error: null,
            success: null,
            steps: [
                { step: 1, name: "Requirements" },
                { step: 2, name: "Configuration" },
                { step: 3, name: "Registration" },
            ],
            requirements: {
                requirements: [],
                permissions: [],
                versions: {
                    php: { current: null, minimum: "8.0", supported: false },
                    composer: {
                        current: null,
                        minimum: "2.0",
                        supported: false,
                    },
                    filament: {
                        current: null,
                        minimum: "2.0",
                        supported: false,
                    },
                    livewire: {
                        current: null,
                        minimum: "2.0",
                        supported: false,
                    },
                    node: { current: null, minimum: "14.0", supported: false },
                    npm: { current: null, minimum: "6.0", supported: false },
                },
            },
            form: {
                app_name: "Galeri Sejarah",
                app_url: window.location.origin,
                app_email: "",
                app_phone: "",
                app_address: "",
                db_name: "",
                db_user: "root",
                db_pass: "",
                db_host: "127.0.0.1",
            },
            company: {
                name: "",
                email: "",
                phone: "",
                address: "",
            },
            admin: {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
            },
        };
    },
    computed: {
        phpRequirements() {
            return this.requirements.requirements.filter(
                (req) => req.type === "PHP"
            );
        },
        allRequirementsMet() {
            // Check if all versions are supported
            const versionsSupported = Object.values(
                this.requirements.versions
            ).every((v) => v.supported);

            // Check if all PHP requirements are met
            const phpRequirementsMet = this.phpRequirements.every(
                (req) => req.status
            );

            // Check if all permissions are correct
            const permissionsCorrect = this.requirements.permissions.every(
                (perm) => perm.status
            );

            return (
                versionsSupported && phpRequirementsMet && permissionsCorrect
            );
        },
    },
    mounted() {
        this.fetchRequirements();
    },
    methods: {
        async fetchRequirements() {
            this.loading = true;
            try {
                // Simulate API call - replace with actual API endpoint
                const response = await axios.get("/api/requirements");
                this.requirements = response.data;

                // For demo purposes, simulate all requirements met
                Object.keys(this.requirements.versions).forEach((key) => {
                    this.requirements.versions[key].supported = true;
                    this.requirements.versions[key].current =
                        this.requirements.versions[key].minimum;
                });

                this.requirements.requirements.forEach((req) => {
                    req.status = true;
                });

                this.requirements.permissions.forEach((perm) => {
                    perm.status = true;
                    perm.current = "0775";
                    perm.required = "0775";
                });
            } catch (error) {
                this.error =
                    "Failed to fetch system requirements. Please check your server configuration.";
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        nextStep() {
            if (this.currentStep < this.steps.length) {
                this.currentStep++;
                sessionStorage.setItem("install_step", this.currentStep);
                this.error = null;
                this.success = null;
            }
        },
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                sessionStorage.setItem("install_step", this.currentStep);
                this.error = null;
                this.success = null;
            }
        },
        async saveConfiguration() {
            this.loading = true;
            this.error = null;
            this.success = null;

            try {
                // Get fresh CSRF token before submitting
                await axios.get("/sanctum/csrf-cookie");

                const response = await axios.post(
                    "/change-database",
                    this.form
                );

                if (response.data.status === 200) {
                    this.success = response.data.message;
                    // Store current step before reload
                    sessionStorage.setItem("install_step", "2");
                    // next step
                    this.nextStep();
                } else {
                    this.error =
                        response.data.error ||
                        "Failed to save configuration. Please check your database credentials.";
                }
            } catch (error) {
                if (error.response) {
                    // Server responded with error
                    this.error =
                        error.response.data.error ||
                        "Failed to save configuration. Please check your database credentials.";
                } else if (error.request) {
                    // Network error
                    this.error = "Network error. Please check your connection.";
                } else {
                    // Other error
                    this.error =
                        error.message ||
                        "Failed to save configuration. Please try again.";
                }
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        async completeInstallation() {
            if (this.admin.password !== this.admin.password_confirmation) {
                this.error = "Password confirmation does not match";
                return;
            }

            this.loading = true;
            this.error = null;

            try {
                const payload = {
                    app_name: this.company.name,
                    app_url: window.location.origin,
                    app_email: this.company.email,
                    app_phone: this.company.phone,
                    app_address: this.company.address,
                    admin: {
                        name: this.admin.name,
                        email: this.admin.email,
                        password: this.admin.password,
                        password_confirmation: this.admin.password_confirmation,
                    },
                };

                const response = await axios.post("/install", payload, {
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    timeout: 300000, // 5 minutes timeout
                });

                if (response.data.success) {
                    this.success = response.data.message;
                    // Redirect to login after 3 seconds
                    setTimeout(() => {
                        window.location.href = "/login";
                    }, 3000);
                } else {
                    this.error =
                        response.data.message ||
                        "Installation failed. Please try again.";
                }
            } catch (error) {
                this.error =
                    error.response?.data?.error ||
                    "Failed to complete installation. Please try again.";
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
    },
    watch: {
        "company.email": {
            handler(newValue) {
                this.admin.email = newValue;
            },
        },
    },
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
